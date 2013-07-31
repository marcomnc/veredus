<?php
/**
 * Innoexts
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the InnoExts Commercial License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://innoexts.com/commercial-license-agreement
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@innoexts.com so we can send you a copy immediately.
 * 
 * @category    Innoexts
 * @package     Innoexts_StorePricing
 * @copyright   Copyright (c) 2012 Innoexts (http://www.innoexts.com)
 * @license     http://innoexts.com/commercial-license-agreement  InnoExts Commercial License
 */

/**
 * Price indexer resource
 * 
 * @category   Innoexts
 * @package    Innoexts_StorePricing
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_StorePricing_Model_Mysql4_Catalog_Product_Indexer_Price 
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price 
{
    /**
     * Get store pricing helper
     * 
     * @return Innoexts_StorePricing_Helper_Data
     */
    protected function getStorePricingHelper()
    {
        return Mage::helper('storepricing');
    }
    /**
     * Get version helper
     * 
     * @return Innoexts_InnoCore_Helper_Version
     */
    protected function getVersionHelper()
    {
        return $this->getStorePricingHelper()->getVersionHelper();
    }
    /**
     * Get base currency expression
     * 
     * @param string $website
     * @return string
     */
    /*
    protected function getBaseCurrencyExpr($website)
    {
        return $this->getCurrencyPricingHelper()->getBaseCurrencyExpr($website);
    }
    */
    /**
     * Get currency price expression
     * 
     * @param string $price
     * @param string $currency
     * @return string
     */
    /*
    protected function getCurrencyPriceExpr($price, $defaultPrice, $rate)
    {
        return $this->getCurrencyPricingHelper()->getCurrencyPriceExpr($price, $defaultPrice, $rate);
    }
    */
    /**
     * Prepare tier price index table
     *
     * @param int|array $entityIds the entity ids limitation
     * @return Innoexts_CurrencyPricing_Model_Mysql4_Catalog_Product_Indexer_Price
     */
    protected function _prepareTierPriceIndex($entityIds = null)
    {
        $helper         = $this->getStorePricingHelper();
        $write          = $this->_getWriteAdapter();
        $table          = $this->_getTierPriceIndexTable();
        $write->delete($table);
        if ($this->getVersionHelper()->isGe1600()) {
            $price = $write->getCheckSql('tp.website_id = 0', 'ROUND(tp.value * cwd.rate, 4)', 'tp.value');
        } else {
            $price = new Zend_Db_Expr('IF(tp.website_id=0, ROUND(tp.value * cwd.rate, 4), tp.value)');
        }
        $select = $write->select()
            ->from(array('tp' => $this->getValueTable('catalog/product', 'tier_price')), array('entity_id'))
            ->join(
                array('cg' => $this->getTable('customer/customer_group')),
                'tp.all_groups = 1 OR (tp.all_groups = 0 AND tp.customer_group_id = cg.customer_group_id)',
                array('customer_group_id'))
            ->join(
                array('cw' => $this->getTable('core/website')),
                'tp.website_id = 0 OR tp.website_id = cw.website_id',
                array('website_id'))
            ->join(
                array('csg' => $this->getTable('core/store_group')), 
                'csg.website_id = cw.website_id', 
                array())
            ->join(
                array('cs' => $this->getTable('core/store')), 
                'csg.group_id = cs.group_id AND (tp.store_id = 0 OR tp.store_id = cs.store_id)', 
                array('store_id'))
            ->join(
                array('cwd' => $this->_getWebsiteDateTable()),
                'cw.website_id = cwd.website_id',
                array())
            ->where('cw.website_id != 0')
            ->where('cs.store_id != 0')
            ->columns(new Zend_Db_Expr("MIN({$price})"))
            ->group(array('tp.entity_id', 'cg.customer_group_id', 'cw.website_id', 'cs.store_id'));
        if (!empty($entityIds)) {
            $select->where('tp.entity_id IN(?)', $entityIds);
        }
        $query = $select->insertFromSelect($table);
        $write->query($query);
        return $this;
    }
}