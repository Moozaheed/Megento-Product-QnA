<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Block\Question;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Question form block
 */
class Form extends Template
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var \Magento\Catalog\Model\Product|null
     */
    private $product = null;

    /**
     * @param Context $context
     * @param RequestInterface $request
     * @param ProductRepositoryInterface $productRepository
     * @param CustomerSession $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        ProductRepositoryInterface $productRepository,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Get product
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        if ($this->product === null) {
            $productId = (int)$this->request->getParam('product_id');
            if ($productId) {
                try {
                    $this->product = $this->productRepository->getById($productId);
                } catch (\Exception $e) {
                    return null;
                }
            }
        }
        return $this->product;
    }

    /**
     * Get save URL
     *
     * @return string
     */
    public function getSaveUrl(): string
    {
        return $this->getUrl('productqna/question/save');
    }

    /**
     * Get customer name
     *
     * @return string
     */
    public function getCustomerName(): string
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer()->getName();
        }
        return '';
    }

    /**
     * Get customer email
     *
     * @return string
     */
    public function getCustomerEmail(): string
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer()->getEmail();
        }
        return '';
    }

    /**
     * Is customer logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }
}
