<?php
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */
namespace Mageants\MultipleWishlist\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $setup->getConnection()->addColumn(
            $setup->getTable('wishlist_item'),
            'mage_wishlist_category',
            array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => false,
                'default' => null,
                'comment' => 'Wishlist Category'
            )
        );
        $setup->endSetup();
    }
}
