<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Block\Customer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Vendor\ProductQnA\Model\ResourceModel\Question\Collection;
use Vendor\ProductQnA\Model\ResourceModel\Question\CollectionFactory;
use Vendor\ProductQnA\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;

/**
 * Customer Questions Block
 */
class Questions extends Template
{
    /**
     * @var CollectionFactory
     */
    private $questionCollectionFactory;

    /**
     * @var AnswerCollectionFactory
     */
    private $answerCollectionFactory;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Collection|null
     */
    private $questions = null;

    /**
     * @param Context $context
     * @param CollectionFactory $questionCollectionFactory
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $questionCollectionFactory,
        AnswerCollectionFactory $answerCollectionFactory,
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * Get customer questions collection
     *
     * @return Collection
     */
    public function getQuestions(): Collection
    {
        if ($this->questions === null) {
            $customerEmail = $this->customerSession->getCustomer()->getEmail();
            
            /** @var Collection $collection */
            $collection = $this->questionCollectionFactory->create();
            $collection->addFieldToFilter('customer_email', $customerEmail)
                ->setOrder('created_at', 'DESC');

            // Apply pagination: default 5 per page
            $page = (int)$this->getRequest()->getParam('question_page', 1);
            $collection->setPageSize(5)->setCurPage($page);
            
            $this->questions = $collection;
        }
        
        return $this->questions;
    }

    /**
     * Get answer for a question
     *
     * @param int $questionId
     * @return \Vendor\ProductQnA\Model\Answer|null
     */
    public function getAnswer(int $questionId)
    {
        $answerCollection = $this->answerCollectionFactory->create();
        $answerCollection->addFieldToFilter('question_id', $questionId)
            ->addFieldToFilter('status', 'approved')
            ->setPageSize(1);
        
        return $answerCollection->getFirstItem()->getId() ? $answerCollection->getFirstItem() : null;
    }

    /**
     * Get product by ID
     *
     * @param int $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    public function getProduct(int $productId)
    {
        try {
            return $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Get status label
     *
     * @param string $status
     * @return string
     */
    public function getStatusLabel(string $status): string
    {
        $labels = [
            'pending' => __('Pending'),
            'approved' => __('Approved'),
            'answered' => __('Answered'),
            'rejected' => __('Rejected')
        ];
        
        return (string)($labels[$status] ?? __('Unknown'));
    }

    /**
     * Get status icon
     *
     * @param string $status
     * @return string
     */
    public function getStatusIcon(string $status): string
    {
        $icons = [
            'pending' => '⏱',
            'approved' => '✓',
            'answered' => '✓✓',
            'rejected' => '✗'
        ];
        
        return $icons[$status] ?? '';
    }

    /**
     * Get status class
     *
     * @param string $status
     * @return string
     */
    public function getStatusClass(string $status): string
    {
        $classes = [
            'pending' => 'status-pending',
            'approved' => 'status-approved',
            'answered' => 'status-answered',
            'rejected' => 'status-rejected'
        ];
        
        return $classes[$status] ?? '';
    }

    /**
     * Check if question has answer
     *
     * @param int $questionId
     * @return bool
     */
    public function hasAnswer(int $questionId): bool
    {
        return $this->getAnswer($questionId) !== null;
    }

    /**
     * Get product URL
     *
     * @param int $productId
     * @return string
     */
    public function getProductUrl(int $productId): string
    {
        $product = $this->getProduct($productId);
        return $product ? $product->getProductUrl() : '#';
    }

    /**
     * Convert numeric status to string status
     * Treats STATUS_ARCHIVED (4) as "rejected"
     *
     * @param int $numericStatus
     * @return string
     */
    public function convertStatusToString(int $numericStatus): string
    {
        $statusMap = [
            0 => 'pending',      // STATUS_PENDING
            1 => 'approved',     // STATUS_APPROVED
            2 => 'rejected',     // STATUS_REJECTED
            3 => 'answered',     // STATUS_ANSWERED
            4 => 'rejected'      // STATUS_ARCHIVED - treat as rejected
        ];
        
        return $statusMap[$numericStatus] ?? 'pending';
    }

    /**
     * Format question date
     *
     * @param string $date
     * @return string
     */
    public function formatQuestionDate(string $date): string
    {
        return $this->formatTime($date, \IntlDateFormatter::MEDIUM);
    }
}
