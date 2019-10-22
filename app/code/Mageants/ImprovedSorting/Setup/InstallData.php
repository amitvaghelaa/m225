<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $_sortorderFactory;

    public function __construct(\Mageants\ImprovedSorting\Model\SortOrderFactory $SortOrderFactory)
    {
        $this->_sortorderFactory = $SortOrderFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $data1 = [
            'attribute_code' => "position",
            'attribute_label' => "Position",
            'sortorder'       => 1
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data1)->save();

        $data2 = [
            'attribute_code' => "name",
            'attribute_label' => "Product Name",
            'sortorder'       => 2
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data2)->save();

        $data3 = [
            'attribute_code' => "price",
            'attribute_label' => "Price",
            'sortorder'       => 3
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data3)->save();

        $data4 = [
            'attribute_code' => "bestsellers",
            'attribute_label' => "Best Sellers",
            'sortorder'       => 4
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data4)->save();

        $data5 = [
            'attribute_code' => "most_viewed",
            'attribute_label' => "Most Viewed",
            'sortorder'       => 5
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data5)->save();

        $data6 = [
            'attribute_code' => "wished",
            'attribute_label' => "Now in Wishlists",
            'sortorder'       => 6
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data6)->save();

        $data7 = [
            'attribute_code' => "reviews_count",
            'attribute_label' => "Reviews Count",
            'sortorder'       => 7
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data7)->save();

        $data8 = [
            'attribute_code' => "rating_summary",
            'attribute_label' => "Top Rated",
            'sortorder'       => 8
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data8)->save();

        $data9 = [
            'attribute_code' => "created_at",
            'attribute_label' => "New",
            'sortorder'       => 9
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data9)->save();

        $data10 = [
            'attribute_code' => "saving",
            'attribute_label' => "Biggest Saving",
            'sortorder'       => 10
        ];

        $post = $this->_sortorderFactory->create();
        $post->addData($data10)->save();
    }
}