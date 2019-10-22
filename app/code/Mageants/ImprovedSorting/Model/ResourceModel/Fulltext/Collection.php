<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Model\ResourceModel\Fulltext;

use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection as CollectionCore;

class Collection extends CollectionCore {

	public function getLastPageNumber()
    {
        $collectionSize = count($this->getAllIds());
        if (0 === $collectionSize) {
            return 1;
        } elseif ($this->_pageSize) {
            return ceil($collectionSize / $this->_pageSize);
        } else {
            return 1;
        }
    }
}
