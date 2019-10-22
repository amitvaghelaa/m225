<?php
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */ 
namespace Mageants\MultipleWishlist\Model;
use Magento\Framework\Model\AbstractModel;

class Wishlist extends AbstractModel
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Mageants\MultipleWishlist\Model\ResourceModel\Wishlist::class);
    }
    
}
