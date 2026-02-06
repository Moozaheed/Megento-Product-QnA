<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Api\Data;

/**
 * Question interface
 */
interface QuestionInterface
{
    const QUESTION_ID = 'question_id';
    const PRODUCT_ID = 'product_id';
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMER_NAME = 'customer_name';
    const CUSTOMER_EMAIL = 'customer_email';
    const QUESTION_TEXT = 'question_text';
    const STATUS = 'status';
    const HELPFUL_COUNT = 'helpful_count';
    const VISIBILITY = 'visibility';
    const ANSWER_PREFERENCE = 'answer_preference';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_ANSWERED = 3;
    const STATUS_ARCHIVED = 4;

    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_PRIVATE = 0;
    
    const PREFERENCE_AI = 'ai';
    const PREFERENCE_ADMIN = 'admin';

    /**
     * Get question ID
     *
     * @return int|null
     */
    public function getQuestionId(): ?int;

    /**
     * Set question ID
     *
     * @param int $questionId
     * @return $this
     */
    public function setQuestionId(int $questionId): self;

    /**
     * Get product ID
     *
     * @return int
     */
    public function getProductId(): int;

    /**
     * Set product ID
     *
     * @param int $productId
     * @return $this
     */
    public function setProductId(int $productId): self;

    /**
     * Get customer ID
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set customer ID
     *
     * @param int|null $customerId
     * @return $this
     */
    public function setCustomerId(?int $customerId): self;

    /**
     * Get customer name
     *
     * @return string
     */
    public function getCustomerName(): string;

    /**
     * Set customer name
     *
     * @param string $customerName
     * @return $this
     */
    public function setCustomerName(string $customerName): self;

    /**
     * Get customer email
     *
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail(string $customerEmail): self;

    /**
     * Get question text
     *
     * @return string
     */
    public function getQuestionText(): string;

    /**
     * Set question text
     *
     * @param string $questionText
     * @return $this
     */
    public function setQuestionText(string $questionText): self;

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self;

    /**
     * Get helpful count
     *
     * @return int
     */
    public function getHelpfulCount(): int;

    /**
     * Set helpful count
     *
     * @param int $helpfulCount
     * @return $this
     */
    public function setHelpfulCount(int $helpfulCount): self;

    /**
     * Get visibility
     *
     * @return int
     */
    public function getVisibility(): int;

    /**
     * Set visibility
     *
     * @param int $visibility
     * @return $this
     */
    public function setVisibility(int $visibility): self;

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): self;

    /**
     * Get answer preference
     *
     * @return string
     */
    public function getAnswerPreference(): string;

    /**
     * Set answer preference
     *
     * @param string $preference
     * @return $this
     */
    public function setAnswerPreference(string $preference): self;
}
