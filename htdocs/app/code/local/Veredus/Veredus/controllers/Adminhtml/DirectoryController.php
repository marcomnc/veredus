<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Doctor
 */
class Veredus_Veredus_Adminhtml_DirectoryController extends Mage_Adminhtml_Controller_Action {
    
    public function _initAction()
    {
        $this->loadLayout()
            ->_addBreadcrumb(Mage::helper('veredus')->__('veredus'), Mage::helper('veredus')->__('veredus'))
            ->_addBreadcrumb(Mage::helper('veredus')->__('Country Manager'), Mage::helper('veredus')->__('Country Manager'));
        return $this;
    }

    
    public function checkAction() {
        
        $this->_title($this->__('Country Manager'))->_title($this->__('Verifica lista Paesi'));
        
        $this->_initAction()
            ->_setActiveMenu('veredus/checkcountry')
            ->_addBreadcrumb(Mage::helper('veredus')->__('Controllo Paesi'), Mage::helper('veredus')->__('Controllo Paesi'));

        $gridBlock = $this->getLayout()->createBlock('veredus/adminhtml_directory_check_check','grid');
        
        $this->_addContent($gridBlock);
        
        $this->renderLayout();

        
    }
    
    public function exportcheckAction () {        
        
        $fileType = $this->getRequest()->getParam("exp_type");
        $fileName = 'drectory_check.' . $fileType;

        if ($fileType == "xls") {
            $content = $this->getLayout()->createBlock('veredus/adminhtml_directory_check_check','grid')
                            ->getExcelFile($fileName);
        } else {
            $content = $this->getLayout()->createBlock('veredus/adminhtml_directory_check_check','grid')
                            ->getCsvFile($fileName);
        }
 
        $this->_prepareDownloadResponse($fileName, $content);
        
    }    

    
}

?>
