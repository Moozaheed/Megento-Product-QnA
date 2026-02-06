<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Service;

use Vendor\ProductQnA\Model\QuestionFactory;
use Vendor\ProductQnA\Model\AnswerFactory;
use Vendor\ProductQnA\Model\AiAnswerFactory;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;
use Vendor\ProductQnA\Model\ResourceModel\Answer as AnswerResource;
use Vendor\ProductQnA\Model\ResourceModel\AiAnswer as AiAnswerResource;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * AI Answer Service
 */
class AiAnswerService
{
    /**
     * @var QuestionFactory
     */
    private $questionFactory;

    /**
     * @var AnswerFactory
     */
    private $answerFactory;

    /**
     * @var AiAnswerFactory
     */
    private $aiAnswerFactory;

    /**
     * @var QuestionResource
     */
    private $questionResource;

    /**
     * @var AnswerResource
     */
    private $answerResource;

    /**
     * @var AiAnswerResource
     */
    private $aiAnswerResource;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var AiClient
     */
    private $aiClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param QuestionFactory $questionFactory
     * @param AnswerFactory $answerFactory
     * @param AiAnswerFactory $aiAnswerFactory
     * @param QuestionResource $questionResource
     * @param AnswerResource $answerResource
     * @param AiAnswerResource $aiAnswerResource
     * @param ProductRepositoryInterface $productRepository
     * @param AiClient $aiClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        QuestionFactory $questionFactory,
        AnswerFactory $answerFactory,
        AiAnswerFactory $aiAnswerFactory,
        QuestionResource $questionResource,
        AnswerResource $answerResource,
        AiAnswerResource $aiAnswerResource,
        ProductRepositoryInterface $productRepository,
        AiClient $aiClient,
        LoggerInterface $logger
    ) {
        $this->questionFactory = $questionFactory;
        $this->answerFactory = $answerFactory;
        $this->aiAnswerFactory = $aiAnswerFactory;
        $this->questionResource = $questionResource;
        $this->answerResource = $answerResource;
        $this->aiAnswerResource = $aiAnswerResource;
        $this->productRepository = $productRepository;
        $this->aiClient = $aiClient;
        $this->logger = $logger;
    }

    /**
     * Process AI answer for a question
     *
     * @param int $questionId
     * @return bool
     */
    public function processQuestion(int $questionId): bool
    {
        try {
            $startTime = microtime(true);

            // Load question
            $question = $this->questionFactory->create();
            $this->questionResource->load($question, $questionId);

            if (!$question->getQuestionId()) {
                $this->logger->error('Question not found: ' . $questionId);
                return false;
            }

            // Get product information
            $product = $this->productRepository->getById($question->getProductId());

            // Prepare context for AI
            $context = $this->prepareProductContext($product, $question);
            
            // Log the context being sent
            $this->logger->info('Product context for AI:', $context);

            // Call AI service to generate answer
            $aiResponse = $this->aiClient->generateAnswer($context);

            $processingTime = (int)((microtime(true) - $startTime) * 1000);

            if ($aiResponse && isset($aiResponse['answer'])) {
                // Check if AI answer already exists for this question
                $aiAnswer = $this->aiAnswerFactory->create();
                $this->aiAnswerResource->load($aiAnswer, $questionId, 'question_id');
                
                if (!$aiAnswer->getAiAnswerId()) {
                    // Create new AI answer
                    $aiAnswer->setQuestionId($questionId);
                    $aiAnswer->setAiModelName($aiResponse['model'] ?? 'qwen-2.5-3b');
                    $aiAnswer->setAiAnswerText($aiResponse['answer']);
                    $aiAnswer->setProcessingTimeMs($processingTime);
                    $aiAnswer->setStatus(1); // Published
                    $this->aiAnswerResource->save($aiAnswer);
                } else {
                    // Update existing AI answer
                    $aiAnswer->setAiAnswerText($aiResponse['answer']);
                    $aiAnswer->setProcessingTimeMs($processingTime);
                    $this->aiAnswerResource->save($aiAnswer);
                }

                // Create customer-facing answer
                $answer = $this->answerFactory->create();
                $answer->setQuestionId($questionId);
                $answer->setAnswerText($aiResponse['answer']);
                $answer->setStatus(1); // Published
                $answer->setIsAiGenerated(1);
                $answer->setAiAnswerId($aiAnswer->getAiAnswerId());
                $answer->setAdminUserId(null); // NULL for AI answers (no admin user)
                $this->answerResource->save($answer);

                // Update question status to "Answered" (3)
                $question->setStatus(3);
                $this->questionResource->save($question);

                $this->logger->info('AI answer generated for question ' . $questionId);
                return true;
            }

            $this->logger->error('AI service returned empty response for question ' . $questionId);
            return false;

        } catch (\Exception $e) {
            $this->logger->error('Error processing AI answer: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Prepare product context for AI
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \Vendor\ProductQnA\Model\Question $question
     * @return array
     */
    private function prepareProductContext($product, $question): array
    {
        // Get description from custom attributes
        $description = '';
        $shortDescription = '';
        
        if ($product->getCustomAttribute('description')) {
            $description = strip_tags($product->getCustomAttribute('description')->getValue() ?? '');
        }
        if ($product->getCustomAttribute('short_description')) {
            $shortDescription = strip_tags($product->getCustomAttribute('short_description')->getValue() ?? '');
        }
        
        $context = [
            'question' => $question->getQuestionText(),
            'product' => [
                'name' => $product->getName(),
                'sku' => $product->getSku(),
                'description' => $description,
                'short_description' => $shortDescription,
                'price' => $product->getPrice(),
                'type' => $product->getTypeId(),
            ]
        ];

        // Add custom attributes if available
        if ($product->getCustomAttribute('color')) {
            $context['product']['color'] = $product->getCustomAttribute('color')->getValue();
        }
        if ($product->getCustomAttribute('size')) {
            $context['product']['size'] = $product->getCustomAttribute('size')->getValue();
        }
        if ($product->getCustomAttribute('material')) {
            $context['product']['material'] = $product->getCustomAttribute('material')->getValue();
        }
        if ($product->getCustomAttribute('manufacturer')) {
            $context['product']['manufacturer'] = $product->getCustomAttribute('manufacturer')->getValue();
        }

        // Add category information
        if ($product->getCustomAttribute('category_ids')) {
            $context['product']['categories'] = $product->getCustomAttribute('category_ids')->getValue();
        }
        
        $this->logger->info('Prepared product context: ' . json_encode($context));

        return $context;
    }
}
