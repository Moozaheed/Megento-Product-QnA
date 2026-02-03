<?php
/**
 * Copyright © Vendor. All rights reserved.
 */
declare(strict_types=1);

namespace Vendor\ProductQnA\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Question Status Options
 */
class QuestionStatus implements OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Pending')],
            ['value' => 1, 'label' => __('Approved')],
            ['value' => 2, 'label' => __('Rejected')],
            ['value' => 3, 'label' => __('Answered')],
            ['value' => 4, 'label' => __('Archived')]
        ];
    }
}
