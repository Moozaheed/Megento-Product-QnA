<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Model\ResourceModel\Answer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\ProductQnA\Model\Answer as AnswerModel;
use Vendor\ProductQnA\Model\ResourceModel\Answer as AnswerResource;

/**
 * Answer Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'answer_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(AnswerModel::class, AnswerResource::class);
    }
}
