<?php

namespace Mageants\BannerSlider\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;

use Magento\Framework\Setup\ModuleContextInterface;

use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements  UpgradeSchemaInterface

{

public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
{
		$setup->startSetup();

			if (version_compare($context->getVersion(), '2.0.5') < 0) {

				// Get module table

				$tableName = $setup->getTable('mageants_bannerslider_slides');

				// Check if the table already exists

				if ($setup->getConnection()->isTableExists($tableName) == true) {

				// Declare data

				$columns = [

				'title_font_size' => [

				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,

				'nullable' => true,

				'comment' => 'Title Font Size',

				],

				];

				$connection = $setup->getConnection();

					foreach ($columns as $name => $definition) {

					$connection->addColumn($tableName, $name, $definition);

					}
 
				}

			}
			
		$setup->endSetup();

	}

}