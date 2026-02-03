<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Block\Product\View;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Vendor\ProductQnA\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Vendor\ProductQnA\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use Vendor\ProductQnA\Api\Data\QuestionInterface;

/**
 * Product Questions Block
 */
class Questions extends Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var QuestionCollectionFactory
     */
    private $questionCollectionFactory;

    /**
     * @var AnswerCollectionFactory
     */
    private $answerCollectionFactory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \Vendor\ProductQnA\Model\ResourceModel\Question\Collection|null
     */
    private $questionCollection = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param QuestionCollectionFactory $questionCollectionFactory
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        QuestionCollectionFactory $questionCollectionFactory,
        AnswerCollectionFactory $answerCollectionFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->objectManager = $objectManager;
        parent::__construct($context, $data);
    }

    /**
     * Get current product
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * Get approved questions for current product
     *
     * @return \Vendor\ProductQnA\Model\ResourceModel\Question\Collection
     */
    public function getQuestions()
    {
        if ($this->questionCollection === null) {
            $product = $this->getProduct();
            if ($product && $product->getId()) {
                $this->questionCollection = $this->questionCollectionFactory->create();
                $this->questionCollection
                    ->addFieldToFilter('product_id', $product->getId())
                    ->addFieldToFilter('status', [
                        'in' => [QuestionInterface::STATUS_APPROVED, QuestionInterface::STATUS_ANSWERED]
                    ])
                    ->addFieldToFilter('visibility', QuestionInterface::VISIBILITY_PUBLIC)
                    ->setOrder('created_at', 'DESC');
            }
        }
        return $this->questionCollection;
    }

    /**
     * Get answers for a question
     *
     * @param int $questionId
     * @return \Vendor\ProductQnA\Model\ResourceModel\Answer\Collection
     */
    public function getAnswers(int $questionId)
    {
        $collection = $this->answerCollectionFactory->create();
        $collection
            ->addFieldToFilter('question_id', $questionId)
            ->addFieldToFilter('status', 1)
            ->setOrder('created_at', 'ASC');
        return $collection;
    }

    /**
     * Get total question count
     *
     * @return int
     */
    public function getQuestionCount(): int
    {
        $questions = $this->getQuestions();
        return $questions ? $questions->getSize() : 0;
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

    /**
     * Get ask question URL
     *
     * @return string
     */
    public function getAskQuestionUrl(): string
    {
        $product = $this->getProduct();
        if ($product && $product->getId()) {
            return $this->getUrl('productqna/question/form', ['product_id' => $product->getId()]);
        }
        return '#';
    }

    /**
     * Get admin user name by ID
     *
     * @param int|null $userId
     * @return string
     */
    public function getAdminUserName(?int $userId): string
    {
        if (!$userId) {
            return __('Store Admin')->render();
        }

        try {
            // Use ObjectManager to avoid frontend dependency issues
            $userFactory = $this->objectManager->get(\Magento\User\Model\UserFactory::class);
            $user = $userFactory->create()->load($userId);
            if ($user->getId()) {
                return $user->getFirstname() . ' ' . $user->getLastname();
            }
        } catch (\Exception $e) {
            // User model might not be available or user not found
            return __('Store Admin')->render();
        }

        return __('Store Admin')->render();
    }
}
