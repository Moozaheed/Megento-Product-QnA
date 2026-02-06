<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Api\Data;

/**
 * Answer interface
 */
interface AnswerInterface
{
    const ANSWER_ID = 'answer_id';
    const QUESTION_ID = 'question_id';
    const ADMIN_USER_ID = 'admin_user_id';
    const IS_AI_GENERATED = 'is_ai_generated';
    const AI_ANSWER_ID = 'ai_answer_id';
    const ANSWER_TEXT = 'answer_text';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_PENDING = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * Get answer ID
     *
     * @return int|null
     */
    public function getAnswerId(): ?int;

    /**
     * Set answer ID
     *
     * @param int $answerId
     * @return $this
     */
    public function setAnswerId(int $answerId): self;

    /**
     * Get question ID
     *
     * @return int
     */
    public function getQuestionId(): int;

    /**
     * Set question ID
     *
     * @param int $questionId
     * @return $this
     */
    public function setQuestionId(int $questionId): self;

    /**
     * Get admin user ID
     *
     * @return int|null
     */
    public function getAdminUserId(): ?int;

    /**
     * Set admin user ID
     *
     * @param int|null $adminUserId
     * @return $this
     */
    public function setAdminUserId(?int $adminUserId): self;

    /**
     * Get answer text
     *
     * @return string
     */
    public function getAnswerText(): string;

    /**
     * Set answer text
     *
     * @param string $answerText
     * @return $this
     */
    public function setAnswerText(string $answerText): self;

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
     * Get is AI generated
     *
     * @return int
     */
    public function getIsAiGenerated(): int;

    /**
     * Set is AI generated
     *
     * @param int $isAiGenerated
     * @return $this
     */
    public function setIsAiGenerated(int $isAiGenerated): self;

    /**
     * Get AI Answer ID
     *
     * @return int|null
     */
    public function getAiAnswerId(): ?int;

    /**
     * Set AI Answer ID
     *
     * @param int|null $aiAnswerId
     * @return $this
     */
    public function setAiAnswerId(?int $aiAnswerId): self;
}
