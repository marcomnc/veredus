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

class Veredus_Veredus_Model_Integration_Qta {
    
    protected $_io = null;    
    
    protected $_logMessage = null;


    public function __construct() {
        $this->_io = Mage::getModel('veredus/integration_io',array("veredusimportqta", "veredusimportlog"));
        $this->_logMessage = array();
    }
    
    public function ImportQta() {        
        
        $files = $this->_io->GetFileList();

        if ($files !== false) {
            
            
            foreach ($files as $file) {
                
                $this->_log("Inizio inportazione file {$file['text']}");
               
                $qtys = $this->_io->ReadCsv($file['text'], ';');
                foreach ($qtys as $qt) {
                    $this->_log("recupero il prodotto {$qt[0]}");
                    $productId = Mage::getModel('catalog/product')->getResource()->getIdBySku($qt[0]);
                    if ($productId && $productId> 0) {
                        
                        $qta = preg_replace('/,/', '.', $qt[1]);
                        $isInStock = ($qta > 0) ? 1 : 0;
                        
                        $this->_log("Aggiorono la qta per il prodotto {$qt[0]} ({$qta})");
                        

                        $sql  = "INSERT INTO " . Mage::getSingleton('core/resource')->getTableName('cataloginventory_stock_item');
                        $sql .= "(product_id, stock_id, qty, is_in_stock) VALUES " ;
                        $sql .= "({$productId}, 1,  {$qta} , {$isInStock}) ";
                        $sql .= " ON DUPLICATE KEY UPDATE qty = {$qta}, is_in_stock = {$isInStock}";

                        Mage::getSingleton('core/resource')->getConnection('core_write')->query($sql);
                        
                    } else {
                        $this->_log("non ho trovato il prodotto {$qt[0]}");
                    }
                    
                    
                }
            }
            $this->_log("Sposto il file");
            
            $this->_io->MoveFile2Log($file['text']);
            
            
            $this->_log("Inizio la ricostruzione degli indici");
            //Ricostruisco gli indici relativi alle qtà
            $pProcess = Mage::getModel('index/process')->Load("cataloginventory_stock", 'indexer_code');
            $pProcess->reindexAll();

            $this->_log("terminata la ricorstruzione degli indici");
            $this->_io->SaveLog($this->_logMessage);
        }
        
        
        
    }   
    
    private function _log($message) {
        $dt = date("Ymd") . "T" . date("His") ;
        $this->_logMessage[] = array($dt, $message);
    }
    
   
}
