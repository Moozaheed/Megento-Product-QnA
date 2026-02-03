<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\ProductQnA\Api\Data\QuestionInterface;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;

/**
 * Question Model
 */
class Question extends AbstractModel implements QuestionInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(QuestionResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getQuestionId(): ?int
    {
        return $this->getData(self::QUESTION_ID) ? (int)$this->getData(self::QUESTION_ID) : null;
    }

    /**
     * @inheritDoc
     */
    public function setQuestionId(int $questionId): QuestionInterface
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): int
    {
        return (int)$this->getData(self::PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductId(int $productId): QuestionInterface
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): ?int
    {
        return $this->getData(self::CUSTOMER_ID) ? (int)$this->getData(self::CUSTOMER_ID) : null;
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(?int $customerId): QuestionInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerName(): string
    {
        return (string)$this->getData(self::CUSTOMER_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerName(string $customerName): QuestionInterface
    {
        return $this->setData(self::CUSTOMER_NAME, $customerName);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEmail(): string
    {
        return (string)$this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerEmail(string $customerEmail): QuestionInterface
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * @inheritDoc
     */
    public function getQuestionText(): string
    {
        return (string)$this->getData(self::QUESTION_TEXT);
    }

    /**
     * @inheritDoc
     */
    public function setQuestionText(string $questionText): QuestionInterface
    {
        return $this->setData(self::QUESTION_TEXT, $questionText);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(int $status): QuestionInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getHelpfulCount(): int
    {
        return (int)$this->getData(self::HELPFUL_COUNT);
    }

    /**
     * @inheritDoc
     */
    public function setHelpfulCount(int $helpfulCount): QuestionInterface
    {
        return $this->setData(self::HELPFUL_COUNT, $helpfulCount);
    }

    /**
     * @inheritDoc
     */
    public function getVisibility(): int
    {
        return (int)$this->getData(self::VISIBILITY);
    }

    /**
     * @inheritDoc
     */
    public function setVisibility(int $visibility): QuestionInterface
    {
        return $this->setData(self::VISIBILITY, $visibility);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return (string)$this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt): QuestionInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): string
    {
        return (string)$this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $updatedAt): QuestionInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
