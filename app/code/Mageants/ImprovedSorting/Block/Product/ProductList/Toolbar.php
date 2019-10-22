<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Block\Product\ProductList;

use Magento\Catalog\Helper\Product\ProductList;
use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;
use Mageants\ImprovedSorting\Helper\Data;
use Magento\Framework\App\Request\Http;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{

    /**
     * @var \Mageants\ImprovedSorting\Helper\Data
     */
    protected $_dataHelper;

    protected $_request;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Catalog\Model\Config $catalogConfig,
        ToolbarModel $toolbarModel,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        ProductList $productListHelper,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        Data $dataHelper,
        Http $request,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $catalogSession,
            $catalogConfig,
            $toolbarModel,
            $urlEncoder,
            $productListHelper,
            $postDataHelper,
            $data
        );
      
        $this->_dataHelper = $dataHelper;
        $this->_request = $request;
    }

    public function setCollection($collection)
    {
        $q = $this->_request->getParam('q');
        $showImageInLast = $this->_dataHelper->getShowProductImageInLast();
        $imageInLast = '';
        if ($q):
            if ($showImageInLast == 1):
                $imageInLast = true; else:
                $imageInLast = false;
        endif; else:
            if ($showImageInLast == 2 || $showImageInLast == 1):
                $imageInLast = true; else:
                $imageInLast = false;
        endif;

        endif;

        if ($imageInLast):
            $collection->getSelect()->joinLeft(
                'catalog_product_entity_varchar',
                'e.entity_id = catalog_product_entity_varchar.entity_id AND attribute_id = 88'
            );

        $imageLoadEnd_q1 = new \Zend_Db_Expr(
                "CASE WHEN catalog_product_entity_varchar.value = 'no_selection' THEN 1 ELSE 0 END ASC"
                    );

        $collection->getSelect()->group('e.entity_id')->order($imageLoadEnd_q1);
                    
        $imageLoadEnd_q2 = new \Zend_Db_Expr(
                "CASE WHEN COUNT(catalog_product_entity_varchar.entity_id)>0 AND attribute_id = 88 THEN 0 ELSE 1 END ASC"
                    );
                  
        $collection->getSelect()->group('e.entity_id')->order($imageLoadEnd_q2);

        endif;

        $showOutOfstockProInLast = $this->_dataHelper->getShowOutOfStockProInLast();

        $outOfstockProInLast = '';

        if ($q):
            if ($showOutOfstockProInLast == 1):
                $outOfstockProInLast = true; else:
                $outOfstockProInLast = false;
        endif; else:
            if ($showOutOfstockProInLast == 2 || $showOutOfstockProInLast == 1):
                $outOfstockProInLast = true; else:
                $outOfstockProInLast = false;
        endif;

        endif;

        if ($outOfstockProInLast):
            
            $useQtyOutOfStock =$this->_dataHelper->getUseQtyOutOfStockPro();

        if ($useQtyOutOfStock):

                $outOfSotckProInlast = new \Zend_Db_Expr("CASE WHEN cataloginventory_stock_item.qty < 1 THEN 1 ELSE 0 END ASC"); else:

                $outOfSotckProInlast = new \Zend_Db_Expr("CASE WHEN cataloginventory_stock_item.is_in_stock = 0 THEN 1 ELSE 0 END ASC");
        endif;

        $collection->getSelect()
                    ->joinLeft(
                        'cataloginventory_stock_item',
                        'e.entity_id = cataloginventory_stock_item.product_id'
                    );
        $collection->getSelect()->order($outOfSotckProInlast);

        endif;
        
        
        if ($this->getCurrentOrder()=="bestsellers") {
            $periodDays = $this->_dataHelper->getBestSellerViewPeriod();

            $exculdeOrderSt = $this->_dataHelper->getExcludeOrderSt();

            if ($periodDays):

                   $today = time();
            $lastday = $today - (60*60*24*$periodDays);
             
            $from = date("Y-m-d h:i:s", $lastday);
            $to = date("Y-m-d h:i:s", $today);

            $joinQuery = "sales_order_item.order_id = sales_order.entity_id AND sales_order.status NOT IN  ({$exculdeOrderSt}) AND sales_order_item.created_at BETWEEN '{$from}' AND '{$to}'"; else:

                $joinQuery = "sales_order_item.order_id = sales_order.entity_id AND sales_order.status NOT IN  ({$exculdeOrderSt})";

            endif;
           
            $collection->getSelect()->joinLeft('sales_order_item', 'e.entity_id = sales_order_item.product_id', array('sales_order_item.order_id'));

            $collection->getSelect()->joinLeft('sales_order', $joinQuery, array('sales_order.status',"sum(Case When sales_order.status NOT IN ({$exculdeOrderSt}) Then sales_order_item.qty_ordered Else 0 End) AS qty_ordered"))->order('qty_ordered '.$this->getCurrentDirectionReverse());
            
            $collection->getSelect()->group('e.entity_id');

            $useCustomBestSellerAttri = $this->_dataHelper->getUseCustomBestSellerAttri();

            if ($useCustomBestSellerAttri != ' ') {
                $collection->addAttributeToSort($useCustomBestSellerAttri, $this->getCurrentDirectionReverse());
            }
        } elseif ($this->getCurrentOrder()=="created_at") {
            $useCustomNewestAttri = $this->_dataHelper->getUseCustomNewestAttri();
            
            if ($useCustomNewestAttri != ' ') {
                $collection->addAttributeToSort($useCustomNewestAttri, $this->getCurrentDirectionReverse());
            } else {
                $collection->getSelect()->order('e.created_at '.$this->getCurrentDirectionReverse());
            }
        } elseif ($this->getCurrentOrder() == "most_viewed") {
            $periodDays = $this->_dataHelper->getMostViewPeriod();

            if ($periodDays):

                   $today = time();
            $lastday = $today - (60*60*24*$periodDays);
             
            $from = date("Y-m-d h:i:s", $lastday);
            $to = date("Y-m-d h:i:s", $today);

            $joinQuery = "e.entity_id = report_event.object_id AND report_event.logged_at BETWEEN '{$from}' AND '{$to}'"; else:

                $joinQuery = "e.entity_id = report_event.object_id";

            endif;

            $collection->getSelect()->joinLeft(
                'report_event',
                $joinQuery,
                array('view_count' => 'COUNT(report_event.event_id)','logged_at' => "report_event.logged_at")
            )
                ->group('e.entity_id')
                ->order('view_count '.$this->getCurrentDirectionReverse());

            $useCustomMostviewAttri = $this->_dataHelper->getUseCustomMostviewAttri();

            if ($useCustomMostviewAttri != ' ') {
                $collection->addAttributeToSort($useCustomMostviewAttri, $this->getCurrentDirectionReverse());
            }
        } elseif ($this->getCurrentOrder() == "rating_summary") {
            $countColumn = new \Zend_Db_Expr("COUNT(*)");
            $percentColumn = new \Zend_Db_Expr("SUM(percent)/".$countColumn);

            $collection->getSelect()->joinLeft(
                'rating_option_vote',
                'e.entity_id = rating_option_vote.entity_pk_value',
                array('sum' => 'COUNT(rating_option_vote.percent)','count' => $countColumn,'average' => $percentColumn)
                )
                    ->group('e.entity_id')
                    ->order('average '.$this->getCurrentDirectionReverse());
        } elseif ($this->getCurrentOrder() == "reviews_count") {
            $collection->getSelect()->joinLeft(
                'rating_option_vote',
                'e.entity_id = rating_option_vote.entity_pk_value',
                array('review_id' => 'COUNT(rating_option_vote.review_id)')
            )
                ->group('e.entity_id')
                ->order('review_id '.$this->getCurrentDirectionReverse());
        } elseif ($this->getCurrentOrder() == "wished") {
            $wishlistViewPeriod = $this->_dataHelper->getWishlistViewPeriod();

            if ($wishlistViewPeriod):

                   $today = time();
            $lastday = $today - (60*60*24*$wishlistViewPeriod);
             
            $from = date("Y-m-d h:i:s", $lastday);
            $to = date("Y-m-d h:i:s", $today);

            $joinQuery = "e.entity_id = wishlist_item.product_id AND wishlist_item.added_at BETWEEN '{$from}' AND '{$to}'"; else:

                $joinQuery = "e.entity_id = wishlist_item.product_id";

            endif;

            $collection->getSelect()->joinLeft(
                'wishlist_item',
                $joinQuery,
                array('wishlist_count' => 'COUNT(wishlist_item.product_id)','added_by' => 'wishlist_item.added_at')
                )
                    ->group('e.entity_id')
                     ->order('wishlist_count '.$this->getCurrentDirectionReverse());
        } elseif ($this->getCurrentOrder() == "saving") {
            $biggestSortPercentage = $this->_dataHelper->getBiggestSortPercentage();

            $countColumn = new \Zend_Db_Expr("(price_index.price - price_index.final_price) * 100");
            $percentColumn = new \Zend_Db_Expr($countColumn."
				/ price_index.price ");
            $withPercentage = new \Zend_Db_Expr("CASE WHEN price_index.final_price < price_index.price THEN $percentColumn END ".$this->getCurrentDirectionReverse());
        
            $withoutPercentage = new \Zend_Db_Expr("price_index.price - price_index.final_price ".$this->getCurrentDirectionReverse());

            if ($biggestSortPercentage) {
                $collection->getSelect()
                ->order($withPercentage);
            } else {
                $collection->getSelect()
                ->order($withoutPercentage);
            }
        }
             
    
        $this->_collection = $collection;

        $this->_collection->setCurPage($this->getCurrentPage());

        $limit = (int)$this->getLimit();
        
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }

        if ($this->getCurrentOrder()) {
            $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
        }
            
        return $this;
    }

    public function getCurrentDirectionReverse()
    {
        if ($this->getCurrentDirection() == 'asc') {
            return 'asc';
        } elseif ($this->getCurrentDirection() == 'desc') {
            return 'desc';
        } else {
            return $this->getCurrentDirection();
        }
    }

    public function getTotalNum()
    {
        return count($this->getCollection()->getAllIds());
    }
}
