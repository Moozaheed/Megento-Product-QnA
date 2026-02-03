<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;
use Vendor\ProductQnA\Model\QuestionFactory;

/**
 * Approve question
 */
class Approve extends Action
{
    const ADMIN_RESOURCE = 'Vendor_ProductQnA::questions';

    /**
     * @var QuestionFactory
     */
    private $questionFactory;

    /**
     * @var QuestionResource
     */
    private $questionResource;

    /**
     * @param Context $context
     * @param QuestionFactory $questionFactory
     * @param QuestionResource $questionResource
     */
    public function __construct(
        Context $context,
        QuestionFactory $questionFactory,
        QuestionResource $questionResource
    ) {
        parent::__construct($context);
        $this->questionFactory = $questionFactory;
        $this->questionResource = $questionResource;
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int)$this->getRequest()->getParam('id');

        if ($id) {
            try {
                $question = $this->questionFactory->create();
                $this->questionResource->load($question, $id);
                
                if ($question->getId()) {
                    $question->setStatus(1); // Approved
                    $this->questionResource->save($question);
                    $this->messageManager->addSuccessMessage(__('Question has been approved.'));
                } else {
                    $this->messageManager->addErrorMessage(__('Question not found.'));
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error approving question: %1', $e->getMessage()));
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
