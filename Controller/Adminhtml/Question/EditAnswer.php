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
use Vendor\ProductQnA\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use Vendor\ProductQnA\Model\QuestionFactory;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;
use Vendor\ProductQnA\Api\Data\QuestionInterface;
use Magento\Backend\Model\Auth\Session;

/**
 * Update existing answer
 */
class EditAnswer extends Action
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
     * @var AnswerCollectionFactory
     */
    private $answerCollectionFactory;

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
     * @param AnswerCollectionFactory $answerCollectionFactory
     * @param QuestionFactory $questionFactory
     * @param QuestionResource $questionResource
     * @param Session $authSession
     */
    public function __construct(
        Context $context,
        AnswerFactory $answerFactory,
        AnswerResource $answerResource,
        AnswerCollectionFactory $answerCollectionFactory,
        QuestionFactory $questionFactory,
        QuestionResource $questionResource,
        Session $authSession
    ) {
        parent::__construct($context);
        $this->answerFactory = $answerFactory;
        $this->answerResource = $answerResource;
        $this->answerCollectionFactory = $answerCollectionFactory;
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
        $answerId = (int)$this->getRequest()->getParam('answer_id');
        $answerText = trim($this->getRequest()->getParam('answer_text', ''));

        if (!$questionId || !$answerText) {
            $this->messageManager->addErrorMessage(__('Please provide an answer.'));
            return $resultRedirect->setPath('*/*/answer', ['id' => $questionId]);
        }

        try {
            if ($answerId) {
                // Edit existing answer
                $answer = $this->answerFactory->create();
                $this->answerResource->load($answer, $answerId);
                
                if ($answer->getAnswerId() && $answer->getQuestionId() == $questionId) {
                    $answer->setAnswerText($answerText);
                    $answer->setAdminUserId((int)$this->authSession->getUser()->getId());
                    $this->answerResource->save($answer);
                    
                    $this->messageManager->addSuccessMessage(__('Answer has been updated.'));
                } else {
                    $this->messageManager->addErrorMessage(__('Invalid answer.'));
                }
            } else {
                // Create new answer
                $answer = $this->answerFactory->create();
                $answer->setQuestionId($questionId);
                $answer->setAnswerText($answerText);
                $answer->setAdminUserId((int)$this->authSession->getUser()->getId());
                $answer->setStatus(1); // Published
                
                $this->answerResource->save($answer);
                
                $this->messageManager->addSuccessMessage(__('Answer has been saved.'));
            }
            
            // Update question status to "Answered"
            $question = $this->questionFactory->create();
            $this->questionResource->load($question, $questionId);
            if ($question->getQuestionId()) {
                $question->setStatus(QuestionInterface::STATUS_ANSWERED);
                $this->questionResource->save($question);
            }
            
            return $resultRedirect->setPath('*/*/');
            
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error saving answer: %1', $e->getMessage()));
            return $resultRedirect->setPath('*/*/answer', ['id' => $questionId]);
        }
    }
}
