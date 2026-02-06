<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\ProductQnA\Api\Data\AnswerInterface;
use Vendor\ProductQnA\Model\ResourceModel\Answer as AnswerResource;

/**
 * Answer Model
 */
class Answer extends AbstractModel implements AnswerInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(AnswerResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getAnswerId(): ?int
    {
        return $this->getData(self::ANSWER_ID) ? (int)$this->getData(self::ANSWER_ID) : null;
    }

    /**
     * @inheritDoc
     */
    public function setAnswerId(int $answerId): AnswerInterface
    {
        return $this->setData(self::ANSWER_ID, $answerId);
    }

    /**
     * @inheritDoc
     */
    public function getQuestionId(): int
    {
        return (int)$this->getData(self::QUESTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setQuestionId(int $questionId): AnswerInterface
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * @inheritDoc
     */
    public function getAdminUserId(): ?int
    {
        return $this->getData(self::ADMIN_USER_ID) ? (int)$this->getData(self::ADMIN_USER_ID) : null;
    }

    /**
     * @inheritDoc
     */
    public function setAdminUserId(?int $adminUserId): AnswerInterface
    {
        return $this->setData(self::ADMIN_USER_ID, $adminUserId);
    }

    /**
     * @inheritDoc
     */
    public function getAnswerText(): string
    {
        return (string)$this->getData(self::ANSWER_TEXT);
    }

    /**
     * @inheritDoc
     */
    public function setAnswerText(string $answerText): AnswerInterface
    {
        return $this->setData(self::ANSWER_TEXT, $answerText);
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
    public function setStatus(int $status): AnswerInterface
    {
        return $this->setData(self::STATUS, $status);
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
    public function setCreatedAt(string $createdAt): AnswerInterface
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
    public function setUpdatedAt(string $updatedAt): AnswerInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @inheritDoc
     */
    public function getIsAiGenerated(): int
    {
        return (int)$this->getData(self::IS_AI_GENERATED);
    }

    /**
     * @inheritDoc
     */
    public function setIsAiGenerated(int $isAiGenerated): AnswerInterface
    {
        return $this->setData(self::IS_AI_GENERATED, $isAiGenerated);
    }

    /**
     * @inheritDoc
     */
    public function getAiAnswerId(): ?int
    {
        return $this->getData(self::AI_ANSWER_ID) ? (int)$this->getData(self::AI_ANSWER_ID) : null;
    }

    /**
     * @inheritDoc
     */
    public function setAiAnswerId(?int $aiAnswerId): AnswerInterface
    {
        return $this->setData(self::AI_ANSWER_ID, $aiAnswerId);
    }
}
