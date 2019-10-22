<?php
  /**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */ 
namespace Mageants\MultipleWishlist\Model\ResourceModel;  
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;  
class Wishlist extends AbstractDb
{
	/**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageants_wishlist', 'mage_wishlist_id');
    }
}