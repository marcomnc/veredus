<?php


class Veredus_Veredus_Block_Adminhtml_Directory_Sort extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {     
        $this->addColumn('country_code', array(
            'label' => Mage::helper('veredus')->__('Codice Paese'),
            'style' => 'width:200px',            
        ));
        
        $this->addColumn('order', array(
            'label' => Mage::helper('veredus')->__('Posizione'),
            'style' => 'width:200px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add');
        parent::__construct();
    }
    
}
?>
