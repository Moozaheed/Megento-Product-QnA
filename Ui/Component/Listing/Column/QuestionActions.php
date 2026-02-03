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
use Vendor\ProductQnA\Api\Data\QuestionInterface;

/**
 * Question Actions
 */
class QuestionActions extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
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
                $name = $this->getData('name');
                if (isset($item['question_id'])) {
                    $status = isset($item['status']) ? (int)$item['status'] : 0;
                    $isPending = $status == QuestionInterface::STATUS_PENDING;
                    $isApproved = $status == QuestionInterface::STATUS_APPROVED;
                    $isAnswered = $status == QuestionInterface::STATUS_ANSWERED;
                    $isArchived = $status == QuestionInterface::STATUS_ARCHIVED;
                    
                    // PENDING → Can: Approve, Answer, Archive
                    if ($isPending) {
                        $item[$name]['approve'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/approve',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Approve'),
                            'confirm' => [
                                'title' => __('Approve Question'),
                                'message' => __('Are you sure you want to approve this question?')
                            ]
                        ];
                        $item[$name]['answer'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/answer',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Answer')
                        ];
                        $item[$name]['archive'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/archive',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Archive'),
                            'confirm' => [
                                'title' => __('Archive Question'),
                                'message' => __('Are you sure you want to archive this question?')
                            ]
                        ];
                    }
                    
                    // APPROVED → Can: Answer, Archive
                    if ($isApproved) {
                        $item[$name]['answer'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/answer',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Answer')
                        ];
                        $item[$name]['archive'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/archive',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Archive'),
                            'confirm' => [
                                'title' => __('Archive Question'),
                                'message' => __('Are you sure you want to archive this question?')
                            ]
                        ];
                    }
                    
                    // ANSWERED → Can: Edit Answer, Archive
                    if ($isAnswered) {
                        $item[$name]['answer'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/answer',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Edit Answer')
                        ];
                        $item[$name]['archive'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/archive',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Archive'),
                            'confirm' => [
                                'title' => __('Archive Question'),
                                'message' => __('Are you sure you want to archive this question?')
                            ]
                        ];
                    }
                    
                    // ARCHIVED → Can: Approve, Set to Pending
                    if ($isArchived) {
                        $item[$name]['approve'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/approve',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Approve'),
                            'confirm' => [
                                'title' => __('Approve Question'),
                                'message' => __('Approve this archived question?')
                            ]
                        ];
                        $item[$name]['pending'] = [
                            'href' => $this->urlBuilder->getUrl(
                                'productqna/question/pending',
                                ['id' => $item['question_id']]
                            ),
                            'label' => __('Set to Pending'),
                            'confirm' => [
                                'title' => __('Set to Pending'),
                                'message' => __('Move this question back to pending status?')
                            ]
                        ];
                    }
                    
                    // Delete button (always shown)
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'productqna/question/delete',
                            ['id' => $item['question_id']]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete Question'),
                            'message' => __('Are you sure you want to delete this question?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
