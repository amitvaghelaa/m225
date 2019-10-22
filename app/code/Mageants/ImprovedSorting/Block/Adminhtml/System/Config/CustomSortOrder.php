<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Block\Adminhtml\System\Config;

class CustomSortOrder extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'Mageants_ImprovedSorting::system/config/custom_sort_order.phtml';

    protected $_listSort;

    protected $_sortOrderFactory;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\Config\Source\ListSort $listSort,
        \Mageants\ImprovedSorting\Model\SortOrderFactory $sortOrderObj,
        array $data = []
    ) {
        $this->_listSort = $listSort;
        $this->_sortOrderFactory = $sortOrderObj;
        parent::__construct($context, $data);
    }
    /**
     * Remove scope label
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
    /**
     * insert sort order number
     *
     * @return string
     */
    public function checkSortOrder()
    { 

        $customSortOrder = $this->_listSort->toOptionArray();
        $sortOrderAttri = array();

        foreach ($customSortOrder as $key => $attribute) {

            $sortOrderAttri[] = $attribute['value'];
            $sortOrderObj = $this->_sortOrderFactory->create();
            $sortOrderObj = $sortOrderObj->getCollection()->addFieldToFilter('attribute_code',$attribute['value']);

            $sortOrderCount = '';
            $sortOrderObj1 = $this->_sortOrderFactory->create();
            $sortOrderCount = $sortOrderObj1->getCollection();
            $sortOrderCount->getSelect()->limit(1)->order('sortorder DESC'); 
                      

            if(count($sortOrderCount) >= 1){

                $countSortOrder = $sortOrderCount->getData();
                $sortOrderCount = $countSortOrder[0]['sortorder'];
              
            }else{

                $sortOrderCount = 0;

            }

            if(count($sortOrderObj)<=0){

                $sortOrderObj = $this->_sortOrderFactory->create();
                $sortOrderObj->setAttributeCode($attribute['value']);
                $sortOrderObj->setAttributeLabel($attribute['label']);
                $sortOrderObj->setSortorder($sortOrderCount+1);
                $sortOrderObj->save();

            }

        }

        $customSortOrder = $this->getCustomSortOrder($sortOrderAttri);

        return $customSortOrder;
    }
    /**
     * custom sort order
     *
     * @return string
     */
    public function getCustomSortOrder($sortOrderAttri)
    {
        $sortOrderObj = $this->_sortOrderFactory->create();
        $sortOrderCollection = $sortOrderObj->getCollection();
        $sortOrderCollection->getSelect()->order('sortorder ASC');

        $customSortOrder = array();

        foreach ($sortOrderCollection as $key => $attribute) {
            
            if(in_array($attribute['attribute_code'], $sortOrderAttri)){

                $customSortOrder[$key]['label'] = $attribute['attribute_label'];
                $customSortOrder[$key]['value'] = $attribute['attribute_code'];
                $customSortOrder[$key]['name'] = "groups[improvedSorting_general_settings][fields][custom_sort_order][value][".$attribute['attribute_code']."]";

            } else {

                $sortOrderDeleteObj = $this->_sortOrderFactory->create(); 
                $sortOrderDeleteObj->load($attribute['id']);
                $sortOrderDeleteObj->delete();
            }
           
        }

        return $customSortOrder;
    }
}