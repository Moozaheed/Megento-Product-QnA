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
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Product Link Column
 */
class ProductLink extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param ProductRepositoryInterface $productRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        ProductRepositoryInterface $productRepository,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
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
                if (isset($item['product_id'])) {
                    $productId = $item['product_id'];
                    try {
                        $product = $this->productRepository->getById($productId);
                        $productName = $product->getName();
                        
                        $editUrl = $this->urlBuilder->getUrl(
                            'catalog/product/edit',
                            ['id' => $productId]
                        );
                        
                        $item['product_id'] = sprintf(
                            '<a href="%s" target="_blank" title="View Product">%s</a><br/><small>ID: %d</small>',
                            $editUrl,
                            $productName,
                            $productId
                        );
                    } catch (\Exception $e) {
                        $item['product_id'] = 'Product ID: ' . $productId;
                    }
                }
            }
        }

        return $dataSource;
    }
}
