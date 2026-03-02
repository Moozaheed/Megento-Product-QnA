<?php

/**
 * Copyright © Vendor. All rights reserved.
 */

declare(strict_types=1);

namespace Vendor\ProductQnA\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Uninstall script for Product Q&A module
 */
class Uninstall implements UninstallInterface
{
    /**
     * Module uninstall code
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function uninstall(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ): void {
        $setup->startSetup();

        $connection = $setup->getConnection();

        // Drop tables in reverse order (respecting foreign key constraints)
        $tables = [
            'vendor_product_qna_helpful',
            'vendor_product_qna_answer',
            'vendor_product_qna_question'
        ];

        foreach ($tables as $table) {
            $tableName = $setup->getTable($table);
            if ($connection->isTableExists($tableName)) {
                $connection->dropTable($tableName);
            }
        }

        $setup->endSetup();
    }
}
