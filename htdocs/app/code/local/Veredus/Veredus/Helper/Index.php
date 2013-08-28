<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 *
 * @category    
 * @package        
 * @copyright   Copyright (c) 2013 Mps Sistemi (http://www.mps-sistemi.it)
 * @author      MPS Sistemi S.a.s - Marco Mancinelli <marco.mancinelli@mps-sistemi.it>
 *
 */
class Veredus_Veredus_Helper_Index extends Veredus_Veredus_Helper_Data
{
    protected $_res = null;
    protected $_read = null;
    
    // DCorrisponde con l'ordinamento .....
    protected $_IDX_ORDER = Array('catalog_product_view',
                                    'sendfriend_product',
                                    'catalog_product_compare_add_product',
                                    'checkout_cart_add_product',
                                    'wishlist_add_product',
                                    'wishlist_share');
    
    public function __construct() {
        $this->_res = Mage::getSingleton('core/resource');
        $this->_read = $this->_res->getConnection(Mage_Core_Model_Resource::DEFAULT_READ_RESOURCE);
    } 
    
    public function getProductReport($reportType, $idIsKey = true) {
        
        $gg = Mage::getStoreConfig('veredus/settings/shop_by_plus_gg') + 0;        
        $limit = MAge::getStoreConfig('veredus/settings/shop_by_plus_nritem') + 0;
        
        $select = $this->_read->select()
                        ->from(array('type' => $this->_res->getTableName('report_event_types')))
                        ->join(array('report' => $this->_res->getTableName('report_event')),
                               'type.event_type_id = report.event_type_id')
                        ->where('type.event_name = ?', $reportType);
        
        if ($gg > 0)
            $select->where("date_add(report.logged_at, INTERVAL $gg DAY) > ?", Mage::app()->getLocale()->date()->toString('y-MM-d'));
        
        $select->reset(Varien_Db_Select::COLUMNS)
               ->columns(array("ProductId" => 'report.object_id',
                         "idx" => 'count(*)'))
               ->group(array('report.object_id'))
               ->order('idx DESC');
        
        if ($limit > 0)
            $select->limit($limit);
               
        $ids = array();     
        foreach ($this->_read->query($select)->fetchAll(null, 0) as $row) {
            if (!$idIsKey)
                $ids[] = $row['ProductId'];
            else
                $ids[$row['ProductId']] = 0;
        }
        
        return $ids;
    }
    
    public function getElabType($id) {
        return $this->_IDX_ORDER[$id];
    }
    
}

?>
