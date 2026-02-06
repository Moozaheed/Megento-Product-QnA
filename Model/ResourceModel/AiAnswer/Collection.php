<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Model\ResourceModel\AiAnswer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\ProductQnA\Model\AiAnswer;
use Vendor\ProductQnA\Model\ResourceModel\AiAnswer as AiAnswerResource;

/**
 * AI Answer Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(AiAnswer::class, AiAnswerResource::class);
    }
}
