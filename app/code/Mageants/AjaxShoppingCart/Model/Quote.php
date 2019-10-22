<?php
/**
 * @category  Mageants AjaxShoppingCart
 * @package   Mageants_AjaxShoppingCart
 * @copyright Copyright (c) 2019 Magento
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\AjaxShoppingCart\Model;

class Quote extends \Magento\Quote\Model\Quote
{
    public function getItemById($itemId)
    {
        // return $this->getItemsCollection()->getItemById($itemId);
        foreach ($this->getItemsCollection() as $item) {
            if ($item->getId() == $itemId) {
                return $item;
            }
        }

        return false;
    }

}