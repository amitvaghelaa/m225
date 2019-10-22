<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        
        $installer->startSetup();
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('mageants_improvedSorting'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['auto_increment' => true,'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
                'attribute_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Attribute Code'
            )
            ->addColumn(
                'attribute_label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Attribute Label'
            )->addColumn(
                'sortorder',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                ['default' => null],
                'Sortorder'
            );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
