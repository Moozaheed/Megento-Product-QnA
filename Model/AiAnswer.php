<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\ProductQnA\Model\ResourceModel\AiAnswer as AiAnswerResource;

/**
 * AI Answer Model
 */
class AiAnswer extends AbstractModel
{
    const AI_ANSWER_ID = 'ai_answer_id';
    const QUESTION_ID = 'question_id';
    const AI_MODEL_NAME = 'ai_model_name';
    const AI_ANSWER_TEXT = 'ai_answer_text';
    const PROCESSING_TIME_MS = 'processing_time_ms';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_GENERATED = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(AiAnswerResource::class);
    }

    /**
     * Get AI Answer ID
     *
     * @return int|null
     */
    public function getAiAnswerId(): ?int
    {
        return $this->getData(self::AI_ANSWER_ID) ? (int)$this->getData(self::AI_ANSWER_ID) : null;
    }

    /**
     * Set AI Answer ID
     *
     * @param int $aiAnswerId
     * @return $this
     */
    public function setAiAnswerId(int $aiAnswerId): self
    {
        return $this->setData(self::AI_ANSWER_ID, $aiAnswerId);
    }

    /**
     * Get Question ID
     *
     * @return int
     */
    public function getQuestionId(): int
    {
        return (int)$this->getData(self::QUESTION_ID);
    }

    /**
     * Set Question ID
     *
     * @param int $questionId
     * @return $this
     */
    public function setQuestionId(int $questionId): self
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * Get AI Model Name
     *
     * @return string
     */
    public function getAiModelName(): string
    {
        return (string)$this->getData(self::AI_MODEL_NAME);
    }

    /**
     * Set AI Model Name
     *
     * @param string $modelName
     * @return $this
     */
    public function setAiModelName(string $modelName): self
    {
        return $this->setData(self::AI_MODEL_NAME, $modelName);
    }

    /**
     * Get AI Answer Text
     *
     * @return string
     */
    public function getAiAnswerText(): string
    {
        return (string)$this->getData(self::AI_ANSWER_TEXT);
    }

    /**
     * Set AI Answer Text
     *
     * @param string $answerText
     * @return $this
     */
    public function setAiAnswerText(string $answerText): self
    {
        return $this->setData(self::AI_ANSWER_TEXT, $answerText);
    }

    /**
     * Get Processing Time in Milliseconds
     *
     * @return int|null
     */
    public function getProcessingTimeMs(): ?int
    {
        return $this->getData(self::PROCESSING_TIME_MS) ? (int)$this->getData(self::PROCESSING_TIME_MS) : null;
    }

    /**
     * Set Processing Time in Milliseconds
     *
     * @param int $timeMs
     * @return $this
     */
    public function setProcessingTimeMs(int $timeMs): self
    {
        return $this->setData(self::PROCESSING_TIME_MS, $timeMs);
    }

    /**
     * Get Status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * Set Status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get Created At
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return (string)$this->getData(self::CREATED_AT);
    }

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get Updated At
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return (string)$this->getData(self::UPDATED_AT);
    }

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): self
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
