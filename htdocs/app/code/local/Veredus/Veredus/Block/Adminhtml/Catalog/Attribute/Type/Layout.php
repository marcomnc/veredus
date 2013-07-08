<?php

/**
 *
 * Elenco degli attributi che hanno un layouy particolare.
 * i layout gestiti sono fissi Colori taglie
 *
 * @category    Custom
 * @package     Mage_Catalog
 * @author      Marco Mancinelli
 * 
 */
class Veredus_Veredus_Block_Adminhtml_Catalog_Attribute_Type_Layout extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract 
{   
    
    protected $_layoutType = array();
    protected $_attributeList = array();


    
    public function __construct()
    {     
        $this->_layoutType =array('color' => Mage::helper('veredus')->__('Colore'),
                                      'size' => Mage::helper('veredus')->__('Taglia')
                                     );
        $_attList =  $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
                        ->addHasOptionsFilter()
                        ->addVisibleFilter()
                        ->addFieldToFilter('is_user_defined',1)
                        ->addStoreLabel(Mage::app()->getStore()->getId())
                        ->setOrder('main_table.attribute_id', 'asc')
                        ->load();;

        foreach ($_attList as $_att) {
            if ($_att->getFrontendInput() == "select") {
                $this->_attributeList[$_att->getAttributeCode()] = $_att->getFRontendLabel();
            }
        }

        $this->addColumn('attribute', array(
            'label' => Mage::helper('veredus')->__('Attributo'),
            'style' => 'width:200px',            
            'tag'   => 'SELECT',
            'option'=> $this->_attributeList,
        ));
        
        $this->addColumn('type', array(
            'label' => Mage::helper('veredus')->__('Tipo Layout'),
            'style' => 'width:200px',
            'tag'   => 'SELECT',
            'option'=> $this->_layoutType,
        ));
        
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('veredus')->__('Add Attribute');
        parent::__construct();
    }
}

?>
