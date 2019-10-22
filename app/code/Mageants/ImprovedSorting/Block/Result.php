<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Block;

use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Framework\View\Element\Template\Context;
use Magento\CatalogSearch\Helper\Data;
use Magento\Search\Model\QueryFactory;
use Mageants\ImprovedSorting\Helper\Data as ImprovedSortingHelper ;

class Result extends \Magento\CatalogSearch\Block\Result
{

    protected $_dataHelper;

    public function __construct(
        Context $context,
        LayerResolver $layerResolver,
        Data $catalogSearchData,
        QueryFactory $queryFactory,
        ImprovedSortingHelper $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $layerResolver, $catalogSearchData, $queryFactory, $data);

        $this->_dataHelper = $dataHelper;
    }

    public function setListOrders()
    { 

        $category = $this->catalogLayer->getCurrentCategory();
        /* @var $category \Magento\Catalog\Model\Category */
        $availableOrders = $category->getAvailableSortByOptions();
        unset($availableOrders['position']);
        $availableOrders['relevance'] = __('Relevance');

        $defaultSorting = $this->_dataHelper->getDefaultSortSearchPage();
        
        if(array_key_exists($defaultSorting, $availableOrders)):
           
            $defaultSort = $defaultSorting;

        else:
            
            $defaultSort = 'relevance';

        endif;

        
        $this->getListBlock()->setAvailableOrders(
            $availableOrders
        )->setDefaultDirection(
            'desc'
        )->setDefaultSortBy(
            $defaultSort
        );

        return $this;
    }
}