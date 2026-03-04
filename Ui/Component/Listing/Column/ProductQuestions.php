<?php

/**
 * Copyright © Vendor. All rights reserved.
 */

declare(strict_types=1);

namespace Vendor\ProductQnA\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Vendor\ProductQnA\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Vendor\ProductQnA\Api\Data\QuestionInterface;

/**
 * Product Questions Column - Shows question count with warning icon for pending questions
 */
class ProductQuestions extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var QuestionCollectionFactory
     */
    private $questionCollectionFactory;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param QuestionCollectionFactory $questionCollectionFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        QuestionCollectionFactory $questionCollectionFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->questionCollectionFactory = $questionCollectionFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['entity_id'])) {
                    $productId = (int)$item['entity_id'];
                    
                    // Get question statistics for this product
                    $collection = $this->questionCollectionFactory->create();
                    $collection->addFieldToFilter('product_id', $productId);
                    
                    $totalQuestions = $collection->getSize();
                    
                    // Count pending questions
                    $pendingCollection = $this->questionCollectionFactory->create();
                    $pendingCollection->addFieldToFilter('product_id', $productId)
                        ->addFieldToFilter('status', QuestionInterface::STATUS_PENDING);
                    
                    $pendingCount = $pendingCollection->getSize();
                    
                    // Build the HTML output
                    $html = $this->buildQuestionColumnHtml($totalQuestions, $pendingCount, $productId);
                    
                    $item[$this->getData('name')] = $html;
                }
            }
        }

        return $dataSource;
    }

    /**
     * Build HTML for the questions column
     *
     * @param int $totalQuestions
     * @param int $pendingCount
     * @param int $productId
     * @return string
     */
    private function buildQuestionColumnHtml(int $totalQuestions, int $pendingCount, int $productId): string
    {
        if ($totalQuestions === 0) {
            return '<span style="color: #999;">0</span>';
        }

        // Build URL with encoded filter parameter
        // If there are pending questions, filter by product_id AND status=pending
        // Otherwise, just filter by product_id
        if ($pendingCount > 0) {
            $filters = base64_encode(json_encode([
                'product_id' => $productId,
                'status' => QuestionInterface::STATUS_PENDING
            ]));
        } else {
            $filters = base64_encode(json_encode(['product_id' => $productId]));
        }
        
        $url = $this->urlBuilder->getUrl('productqna/question/index', [
            'filters' => $filters
        ]);

        $warningIcon = '';
        if ($pendingCount > 0) {
            $warningIcon = '<span style="color: #ff5501; font-size: 16px; font-weight: bold; margin-left: 5px; vertical-align: middle;" title="' 
                . __('%1 pending question(s)', $pendingCount) . '">⚠️</span>';
        }

        $html = '<a href="' . $url . '" onclick="event.stopPropagation();" style="text-decoration: none; display: inline-flex; align-items: center;">';
        $html .= '<span style="color: #1979c3; font-weight: 600; font-size: 14px;">' . $totalQuestions . '</span>';
        $html .= $warningIcon;
        $html .= '</a>';

        return $html;
    }
}
