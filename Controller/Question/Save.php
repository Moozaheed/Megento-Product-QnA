<?php

/**
 * Copyright © Vendor. All rights reserved.
 */

declare(strict_types=1);

namespace Vendor\ProductQnA\Controller\Question;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Vendor\ProductQnA\Model\QuestionFactory;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Save question
 */
class Save extends Action implements HttpPostActionInterface, CsrfAwareActionInterface
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

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
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param QuestionFactory $questionFactory
     * @param QuestionResource $questionResource
     * @param CustomerSession $customerSession
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        QuestionFactory $questionFactory,
        QuestionResource $questionResource,
        CustomerSession $customerSession,
        ProductRepositoryInterface $productRepository
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->questionFactory = $questionFactory;
        $this->questionResource = $questionResource;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $request = $this->getRequest();
        $isAjax = (bool)$request->getParam('isAjax', false);

        $productId = (int)$this->getRequest()->getParam('product_id');
        $questionText = trim($this->getRequest()->getParam('question_text', ''));
        
        // Get customer name and email from request (they're sent as hidden fields for logged-in users)
        $customerName = trim($this->getRequest()->getParam('customer_name', ''));
        $customerEmail = trim($this->getRequest()->getParam('customer_email', ''));
        
        // For logged-in users: fallback to session data if form fields are missing
        if ($this->customerSession->isLoggedIn() && (!$customerName || !$customerEmail)) {
            $customer = $this->customerSession->getCustomer();
            if (!$customerName) {
                $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
            }
            if (!$customerEmail) {
                $customerEmail = (string)$customer->getData('email');
            }
        }

        if (!$productId || !$questionText || !$customerName || !$customerEmail) {
            $errorMsg = __('Please fill all required fields.');
            
            if ($isAjax) {
                $result = $this->jsonFactory->create();
                return $result->setData([
                    'success' => false,
                    'message' => $errorMsg
                ]);
            }
            $this->messageManager->addErrorMessage($errorMsg);
            $resultRedirect = $this->resultRedirectFactory->create();
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
                $customerId = (int)$this->customerSession->getCustomerId();
                $question->setCustomerId($customerId);
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

            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('catalog/product/view', ['id' => $productId]);
        } catch (\Exception $e) {
            // Log the error
            $logger = \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
            $logger->error('ProductQnA Save Error: ' . $e->getMessage(), ['exception' => $e]);
            
            if ($isAjax) {
                $result = $this->jsonFactory->create();
                return $result->setData([
                    'success' => false,
                    'message' => __('Unable to submit question. Please try again.')
                ]);
            }
            $this->messageManager->addErrorMessage(__('Unable to submit question. Please try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('productqna/question/form', ['product_id' => $productId]);
        }
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        // Bypass CSRF for this controller
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        // Bypass CSRF validation - we handle form_key in the form
        return true;
    }
}
