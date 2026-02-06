<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Controller\Question;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Message\ManagerInterface;
use Vendor\ProductQnA\Model\QuestionFactory;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Vendor\ProductQnA\Service\AiAnswerService;
use Psr\Log\LoggerInterface;

/**
 * Save question
 */
class Save implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var QuestionFactory
     */
    private $questionFactory;

    /**
     * @var QuestionResource
     */
    private $questionResource;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var AiAnswerService
     */
    private $aiAnswerService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param JsonFactory $jsonFactory
     * @param ManagerInterface $messageManager
     * @param QuestionFactory $questionFactory
     * @param QuestionResource $questionResource
     * @param CustomerSession $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param AiAnswerService $aiAnswerService
     * @param LoggerInterface $logger
     */
    public function __construct(
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        JsonFactory $jsonFactory,
        ManagerInterface $messageManager,
        QuestionFactory $questionFactory,
        QuestionResource $questionResource,
        CustomerSession $customerSession,
        ProductRepositoryInterface $productRepository,
        AiAnswerService $aiAnswerService,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->jsonFactory = $jsonFactory;
        $this->messageManager = $messageManager;
        $this->questionFactory = $questionFactory;
        $this->questionResource = $questionResource;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->aiAnswerService = $aiAnswerService;
        $this->logger = $logger;
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $isAjax = $this->request->isAjax();
        
        $productId = (int)$this->request->getParam('product_id');
        $questionText = trim($this->request->getParam('question_text', ''));
        $customerName = trim($this->request->getParam('customer_name', ''));
        $customerEmail = trim($this->request->getParam('customer_email', ''));
        $answerPreference = $this->request->getParam('answer_preference', 'admin');

        if (!$productId || !$questionText || !$customerName || !$customerEmail) {
            if ($isAjax) {
                $result = $this->jsonFactory->create();
                return $result->setData([
                    'success' => false,
                    'message' => __('Please fill all required fields.')
                ]);
            }
            $this->messageManager->addErrorMessage(__('Please fill all required fields.'));
            $resultRedirect = $this->redirectFactory->create();
            return $resultRedirect->setPath('productqna/question/form', ['product_id' => $productId]);
        }

        try {
            $product = $this->productRepository->getById($productId);
            
            $question = $this->questionFactory->create();
            $question->setProductId($productId);
            $question->setQuestionText($questionText);
            $question->setCustomerName($customerName);
            $question->setCustomerEmail($customerEmail);
            $question->setAnswerPreference($answerPreference);
            
            // Set customer ID if logged in
            if ($this->customerSession->isLoggedIn()) {
                $question->setCustomerId($this->customerSession->getCustomerId());
            }
            
            // Auto-approve if AI preference, otherwise pending
            if ($answerPreference === 'ai') {
                // Status 1 = Approved (ready for AI to process)
                $question->setStatus(1);
            } else {
                // Status 0 = Pending (waiting for admin approval)
                $question->setStatus(0);
            }
            
            $question->setVisibility(1);
            $question->setHelpfulCount(0);
            
            $this->questionResource->save($question);
            
            // Trigger AI answer generation if preference is 'ai'
            if ($answerPreference === 'ai') {
                try {
                    $this->logger->info('Triggering AI answer generation for question: ' . $question->getQuestionId());
                    $this->aiAnswerService->processQuestion((int)$question->getQuestionId());
                } catch (\Exception $e) {
                    $this->logger->error('Error triggering AI answer generation: ' . $e->getMessage());
                    // Don't fail the whole request if AI fails
                }
            }
            
            if ($isAjax) {
                $result = $this->jsonFactory->create();
                $message = $answerPreference === 'ai' 
                    ? __('Your question has been submitted! AI is generating an answer...')
                    : __('Your question has been submitted and will be reviewed by our team.');
                    
                return $result->setData([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            $successMessage = $answerPreference === 'ai'
                ? __('Your question has been submitted! AI is generating an answer...')
                : __('Your question has been submitted and will be reviewed by our team.');
                
            $this->messageManager->addSuccessMessage($successMessage);
            
            $resultRedirect = $this->redirectFactory->create();
            return $resultRedirect->setPath('catalog/product/view', ['id' => $productId]);
            
        } catch (\Exception $e) {
            if ($isAjax) {
                $result = $this->jsonFactory->create();
                return $result->setData([
                    'success' => false,
                    'message' => __('Unable to submit question. Please try again.')
                ]);
            }
            $this->messageManager->addErrorMessage(__('Unable to submit question. Please try again.'));
            $resultRedirect = $this->redirectFactory->create();
            return $resultRedirect->setPath('productqna/question/form', ['product_id' => $productId]);
        }
    }
}
