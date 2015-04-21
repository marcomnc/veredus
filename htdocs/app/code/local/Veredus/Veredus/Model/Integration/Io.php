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

class Veredus_Veredus_Model_Integration_Io {
    
    protected $_pathType;
    protected $_path;
    protected $_ioFile;
    protected $_fileSize;

    
    protected $_pathTypeLog;
    protected $_pathLog;
    
    protected $_logName;



    public function __construct($pathType) {
        $this->_pathType = Mage::getStoreConfig('veredus/settings/' . $pathType[0]);
        $this->_pathTypeLog = Mage::getStoreConfig('veredus/settings/' . $pathType[1]);
        
    }
    
    public function getIoAdapter()
    {
        if (is_null($this->_ioFile)) {
            $this->_ioFile = new Varien_Io_File();
        }
        return $this->_ioFile;
    }
    
    public function GetFileList() {
        $path = $this->getPath();

        try {
            $this->getIoAdapter()->cd($path);
            $list = $this->getIoAdapter()->ls('*.csv');
        } catch (Exception $ex) {
            //Creo la cartella 
            $this->getIoAdapter()->cd(Mage::getBaseDir('var'));
            $curr = "";
            $folders = split('/', $this->_pathType) ;
            foreach ($folders as $folder) {
                if ($folder != "") {
                    if ($curr != "")
                        $this->getIoAdapter()->cd("{$curr}");
                    $curr = $folder;
                    $this->getIoAdapter()->mkdir($folder);
                }
            }            
        }

        
        if (!$list || sizeof($list) <= 0) {
            return false;
        }
        
        return $list;
        
    }
    
    public function open($fileName, $path = "",  $write = true)
    {
        $mode = $write ? 'w+' : 'r+';
        if ($path == "") {
            $path = $this->getIoAdapter()->pwd();
        }
        $ioConfig = array(
            'path' => $path
        );
        $this->getIoAdapter()->setAllowCreateFolders(true);
        $this->getIoAdapter()->open($ioConfig);
        $this->getIoAdapter()->streamOpen($fileName, $mode);

        $this->_fileSize = 0;

        return $this;
    }
    
    /**
     * Close file
     *
     * @return bool
     */
    public function close()
    {
        return $this->getIoAdapter()->streamClose();
    }
    
    
    public function ReadCsv($fileName, $delimiter = ',', $enclosure = '"') {
        
        
        $csvObject = new Varien_File_Csv();
        
        try {
            return $csvObject->setDelimiter($delimiter)->setEnclosure($enclosure)->getData($this->getPath() .  $fileName);
        } catch (Exception $e) {
            return false;
        }
        
        
    }
    
    public function MoveFile2Log($file) {
        $this->_logName = date("Ymd") . "T" . date("His") . "_" . $file ;
       
        if (file_exists($this->getPathLog())) {
            $this->getIoAdapter()->cd($this->getPath());           
     
            $this->getIoAdapter()->mv($file, '../log'. $this->_logName .".bck" );
        }
        
    }

    public function SaveLog($log ) {
        
        $this->getIoAdapter()->cd($this->getPathLog());

        $this->open($this->_logName. ".log" );
        
        foreach ($log as $row) {
            $this->getIoAdapter()->streamWriteCsv($row,' ', '');
        }
        
        $this->close();
        
    }

    /**
     * Retrieve the path
     *
     * @return string
     */
    public function getPath()
    {
        
        if (is_null($this->_path)) {
            $this->_path = $this->getIoAdapter()->getCleanPath(Mage::getBaseDir('var') . $this->_pathType);
            $this->getIoAdapter()->checkAndCreateFolder($this->_path);
        }
        return $this->_path;
    }
    
    /**
     * Retrieve the path
     *
     * @return string
     */
    public function getPathLog()
    {
        
        if (is_null($this->_pathLog)) {
            $this->_pathLog = $this->getIoAdapter()->getCleanPath(Mage::getBaseDir('var') . $this->_pathTypeLog);
            $this->getIoAdapter()->checkAndCreateFolder($this->_pathLog);
        }
        return $this->_pathLog;
    }
    
}
