<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Vendor\ProductQnA\Model\AnswerFactory;
use Vendor\ProductQnA\Model\ResourceModel\Answer as AnswerResource;
use Vendor\ProductQnA\Model\QuestionFactory;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;
use Vendor\ProductQnA\Api\Data\QuestionInterface;
use Magento\Backend\Model\Auth\Session;

/**
 * Save answer
 */
class SaveAnswer extends Action
{
    const ADMIN_RESOURCE = 'Vendor_ProductQnA::questions';

    /**
     * @var AnswerFactory
     */
    private $answerFactory;

    /**
     * @var AnswerResource
     */
    private $answerResource;

    /**
     * @var QuestionFactory
     */
    private $questionFactory;

    /**
     * @var QuestionResource
     */
    private $questionResource;

    /**
     * @var Session
     */
    private $authSession;

    /**
     * @param Context $context
     * @param AnswerFactory $answerFactory
     * @param AnswerResource $answerResource
     * @param QuestionFactory $questionFactory
     * @param QuestionResource $questionResource
     * @param Session $authSession
     */
    public function __construct(
        Context $context,
        AnswerFactory $answerFactory,
        AnswerResource $answerResource,
        QuestionFactory $questionFactory,
        QuestionResource $questionResource,
        Session $authSession
    ) {
        parent::__construct($context);
        $this->answerFactory = $answerFactory;
        $this->answerResource = $answerResource;
        $this->questionFactory = $questionFactory;
        $this->questionResource = $questionResource;
        $this->authSession = $authSession;
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        $questionId = (int)$this->getRequest()->getParam('question_id');
        $answerText = trim($this->getRequest()->getParam('answer_text', ''));

        if (!$questionId || !$answerText) {
            $this->messageManager->addErrorMessage(__('Please provide an answer.'));
            return $resultRedirect->setPath('*/*/answer', ['id' => $questionId]);
        }

        try {
            $answer = $this->answerFactory->create();
            $answer->setQuestionId($questionId);
            $answer->setAnswerText($answerText);
            $answer->setAdminUserId((int)$this->authSession->getUser()->getId());
            $answer->setStatus(1); // Published
            
            $this->answerResource->save($answer);
            
            // Update question status to "Answered"
            $question = $this->questionFactory->create();
            $this->questionResource->load($question, $questionId);
            if ($question->getQuestionId()) {
                $question->setStatus(QuestionInterface::STATUS_ANSWERED);
                $this->questionResource->save($question);
            }
            
            $this->messageManager->addSuccessMessage(__('Answer has been saved.'));
            return $resultRedirect->setPath('*/*/');
            
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error saving answer: %1', $e->getMessage()));
            return $resultRedirect->setPath('*/*/answer', ['id' => $questionId]);
        }
    }
}
