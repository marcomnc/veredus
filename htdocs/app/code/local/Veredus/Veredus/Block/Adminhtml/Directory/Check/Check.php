<?php

/**
 * Description of Check
 *
 * @author Marcoma
 */
 class Veredus_Veredus_Block_Adminhtml_Directory_Check_Check extends Mage_Adminhtml_Block_Widget_Grid_Container {
     
     public function __construct() {
        $this->_blockGroup = 'veredus';
        $this->_controller = 'adminhtml_directory_check';
        $this->_headerText = Mage::helper('veredus')->__('Controllo Paesi');        
        parent::__construct();
        $this->removeButton('add');
     }
 }
?>
