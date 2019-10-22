<?php
 /**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */  
namespace Mageants\MultipleWishlist\Model\ResourceModel\Wishlist;  
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;  
class Collection extends AbstractCollection
{
	/**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Mageants\MultipleWishlist\Model\Wishlist',
            'Mageants\MultipleWishlist\Model\ResourceModel\Wishlist'
        );
    }
}

