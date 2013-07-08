<?php
/**
 *
 * Option Array con i blocchi statici 
 *
 * @category    Custom
 * @package     Mage_Catalog
 * @author      Marco Mancinelli
 * 
 */

class Veredus_Veredus_Model_Adminhtml_System_Config_Source_Cms_Block
{

    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
             $_blocks = Mage::getModel('cms/block')->getCollection();
             $this->_options[] = array ('value' => null, 'label' => Mage::Helper('veredus')->__('Nessuna Richiesta pricacy'));
             foreach ($_blocks as $_block) {
                 $this->_options[] = array('value' => $_block->getId(), 'label' => $_block->getTitle());
             }
        }
        return $this->_options;
    }

}
