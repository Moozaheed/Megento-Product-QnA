<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Block\Adminhtml\Question;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Vendor\ProductQnA\Model\QuestionFactory;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;
use Vendor\ProductQnA\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\User\Model\UserFactory;

/**
 * Answer form block
 */
class Answer extends Template
{
    /**
     * @var QuestionFactory
     */
    private $questionFactory;

    /**
     * @var QuestionResource
     */
    private $questionResource;

    /**
     * @var AnswerCollectionFactory
     */
    private $answerCollectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @param Context $context
     * @param QuestionFactory $questionFactory
     * @param QuestionResource $questionResource
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param UserFactory $userFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        QuestionFactory $questionFactory,
        QuestionResource $questionResource,
        AnswerCollectionFactory $answerCollectionFactory,
        ProductRepositoryInterface $productRepository,
        UserFactory $userFactory,
        array $data = []
    ) {
        $this->questionFactory = $questionFactory;
        $this->questionResource = $questionResource;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->productRepository = $productRepository;
        $this->userFactory = $userFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get question
     *
     * @return \Vendor\ProductQnA\Model\Question|null
     */
    public function getQuestion()
    {
        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            $question = $this->questionFactory->create();
            $this->questionResource->load($question, $id);
            return $question->getId() ? $question : null;
        }
        return null;
    }

    /**
     * Get product
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        $question = $this->getQuestion();
        if ($question) {
            try {
                return $this->productRepository->getById($question->getProductId());
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Get existing answers
     *
     * @return \Vendor\ProductQnA\Model\ResourceModel\Answer\Collection
     */
    public function getAnswers()
    {
        $question = $this->getQuestion();
        if ($question) {
            $collection = $this->answerCollectionFactory->create();
            $collection->addFieldToFilter('question_id', $question->getQuestionId());
            return $collection;
        }
        return $this->answerCollectionFactory->create();
    }

    /**
     * Get admin user name
     *
     * @param int $userId
     * @return string
     */
    public function getAdminUserName($userId)
    {
        try {
            $user = $this->userFactory->create()->load($userId);
            return $user->getName();
        } catch (\Exception $e) {
            return __('Admin');
        }
    }

    /**
     * Get save URL
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/saveAnswer');
    }

    /**
     * Get back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }
}
