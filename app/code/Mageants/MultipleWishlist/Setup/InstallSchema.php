<?php 
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */ 
namespace Mageants\MultipleWishlist\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
 
class InstallSchema implements InstallSchemaInterface
{
    /**
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
 
        $installer->startSetup();
 
        $table = $installer->getConnection()
            ->newTable($installer->getTable('mageants_wishlist'))
            ->addColumn(
                'mage_wishlist_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
              ->addColumn(
                'mage_wishlist_name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Wishlist Name'
            )
              ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Customer ID'
            )
            ->setComment('Wish Lists');
        $installer->getConnection()->createTable($table);

        $installer->getConnection()->addColumn(
            $installer->getTable('wishlist_item'),
            'mage_wishlist_category',
            array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => false,
                'default' => null,
                'comment' => 'Wishlist Category'
            )
        );

        $installer->endSetup();
    }
 
}