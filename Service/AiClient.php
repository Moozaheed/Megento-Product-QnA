<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Service;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

/**
 * AI Client for communicating with AI service
 */
class AiClient
{
    const XML_PATH_AI_ENABLED = 'productqna/ai/enabled';
    const XML_PATH_AI_SERVICE_URL = 'productqna/ai/service_url';
    const XML_PATH_AI_TIMEOUT = 'productqna/ai/timeout';

    /**
     * @var Curl
     */
    private $curl;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Curl $curl
     * @param Json $json
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        Curl $curl,
        Json $json,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->curl = $curl;
        $this->json = $json;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Generate answer from AI service
     *
     * @param array $context
     * @return array|null
     */
    public function generateAnswer(array $context): ?array
    {
        if (!$this->isEnabled()) {
            $this->logger->warning('AI service is disabled in configuration');
            return null;
        }

        $serviceUrl = $this->getServiceUrl();
        $timeout = (int)$this->scopeConfig->getValue(self::XML_PATH_AI_TIMEOUT) ?: 30;

        try {
            $this->curl->setTimeout($timeout);
            $this->curl->addHeader('Content-Type', 'application/json');

            $payload = $this->json->serialize([
                'question' => $context['question'],
                'product_name' => $context['product']['name'] ?? '',
                'product_description' => $context['product']['description'] ?? '',
                'product_short_description' => $context['product']['short_description'] ?? '',
                'product_price' => $context['product']['price'] ?? 0,
                'product_sku' => $context['product']['sku'] ?? '',
                'product_type' => $context['product']['type'] ?? '',
                'product_color' => $context['product']['color'] ?? null,
                'product_size' => $context['product']['size'] ?? null,
                'product_material' => $context['product']['material'] ?? null,
            ]);

            $this->logger->info('Sending request to AI service: ' . $serviceUrl);
            $this->logger->debug('AI Request payload: ' . $payload);

            $this->curl->post($serviceUrl . '/generate-answer', $payload);
            
            $response = $this->curl->getBody();
            $statusCode = $this->curl->getStatus();

            $this->logger->info('AI service response status: ' . $statusCode);
            $this->logger->debug('AI service response body: ' . $response);

            if ($statusCode !== 200) {
                $this->logger->error('AI service returned error status: ' . $statusCode);
                return null;
            }

            $result = $this->json->unserialize($response);
            
            if (!isset($result['answer'])) {
                $this->logger->error('AI service response missing answer field');
                return null;
            }

            return [
                'answer' => $result['answer'],
                'model' => $result['model'] ?? 'qwen-2.5-3b',
                'processing_time' => $result['processing_time'] ?? 0
            ];

        } catch (\Exception $e) {
            $this->logger->error('AI Client error: ' . $e->getMessage());
            $this->logger->error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Check if AI service is enabled
     *
     * @return bool
     */
    private function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_AI_ENABLED);
    }

    /**
     * Get AI service URL
     *
     * @return string
     */
    private function getServiceUrl(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_AI_SERVICE_URL) ?: 'http://localhost:3000';
    }
}
