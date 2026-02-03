<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Model\ResourceModel\Question;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\ProductQnA\Model\Question as QuestionModel;
use Vendor\ProductQnA\Model\ResourceModel\Question as QuestionResource;

/**
 * Question Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'question_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(QuestionModel::class, QuestionResource::class);
    }
}
