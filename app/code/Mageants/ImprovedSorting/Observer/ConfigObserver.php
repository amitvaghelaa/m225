<?php 

/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\App\RequestInterface;
use Mageants\ImprovedSorting\Model\SortOrderFactory;

class ConfigObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    protected $_request;

    protected $_sortOrderFactory;

    /**
     * @param Logger $logger
     */
    public function __construct(
        Logger $logger,
        RequestInterface $request,
        SortOrderFactory $sortOrderObj
    ) {
        $this->logger = $logger;
        $this->_request = $request;
        $this->_sortOrderFactory = $sortOrderObj;
    }

    public function execute(EventObserver $observer)
    {
        $sortOrder = $this->_request->getPost('groups');
        $customSortOrder = $sortOrder['improvedSorting_general_settings']['fields']['custom_sort_order']['value'];
       
       $sortOrder = 1;
       foreach ($customSortOrder as $key => $order) {

            $sortOrderObj = $this->_sortOrderFactory->create();

            $sortOrderObj = $sortOrderObj->getCollection()->addFieldToFilter('attribute_code',$key);
    
            if(count($sortOrderObj) >= 1){

                $sortOrderCollection = $sortOrderObj->getData();
                $manageSortOrder = $this->_sortOrderFactory->create();

                $manageSortOrder->load($sortOrderCollection[0]['id']);
                $manageSortOrder->setSortorder($sortOrder);
                $manageSortOrder->save();

                $sortOrder++;
            }
           
        }
    }
}