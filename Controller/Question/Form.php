<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Controller\Question;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Display question form
 */
class Form implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @param PageFactory $resultPageFactory
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        PageFactory $resultPageFactory,
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        ProductRepositoryInterface $productRepository,
        ManagerInterface $messageManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $productId = (int)$this->request->getParam('product_id');
        
        if (!$productId) {
            $this->messageManager->addErrorMessage(__('Product ID is required.'));
            $resultRedirect = $this->redirectFactory->create();
            return $resultRedirect->setPath('/');
        }

        try {
            $product = $this->productRepository->getById($productId);
            
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Ask a Question about %1', $product->getName()));
            
            return $resultPage;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Product not found.'));
            $resultRedirect = $this->redirectFactory->create();
            return $resultRedirect->setPath('/');
        }
    }
}
