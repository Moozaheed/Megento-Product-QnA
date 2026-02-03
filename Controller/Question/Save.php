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
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param JsonFactory $jsonFactory
     * @param ManagerInterface $messageManager
     * @param QuestionFactory $questionFactory
     * @param QuestionResource $questionResource
     * @param CustomerSession $customerSession
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        JsonFactory $jsonFactory,
        ManagerInterface $messageManager,
        QuestionFactory $questionFactory,
        QuestionResource $questionResource,
        CustomerSession $customerSession,
        ProductRepositoryInterface $productRepository
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->jsonFactory = $jsonFactory;
        $this->messageManager = $messageManager;
        $this->questionFactory = $questionFactory;
        $this->questionResource = $questionResource;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
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
            
            // Set customer ID if logged in
            if ($this->customerSession->isLoggedIn()) {
                $question->setCustomerId($this->customerSession->getCustomerId());
            }
            
            // Set status to pending (0) for admin approval
            $question->setStatus(0);
            $question->setVisibility(1);
            $question->setHelpfulCount(0);
            
            $this->questionResource->save($question);
            
            if ($isAjax) {
                $result = $this->jsonFactory->create();
                return $result->setData([
                    'success' => true,
                    'message' => __('Your question has been submitted and will be reviewed by our team.')
                ]);
            }
            
            $this->messageManager->addSuccessMessage(
                __('Your question has been submitted and will be reviewed by our team.')
            );
            
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
