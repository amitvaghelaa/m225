<?php
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */ 
namespace Mageants\MultipleWishlist\Model\Source;

class Options implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }
        return $result;
    }
    /**
     * @return array
     */
    public static function getOptionArray($listcollection)
    {
        foreach ($listcollection as $index => $list) {
            $list_name[] = ['value'=>$list->getListId(), 'label'=>$list->getListName()];
        }
        return $list_name;
    }
     /**
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }
    /**
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}