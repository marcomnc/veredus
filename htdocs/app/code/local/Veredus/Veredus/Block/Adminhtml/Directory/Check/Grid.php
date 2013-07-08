<?php

class Veredus_Veredus_Block_Adminhtml_Directory_Check_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('country_id');
        $this->setDefaultSort('Name');
        $this->setDefaultDir('ASC');
        
        //$this->setSaveParametersInSession(true);
    }
    
    protected function _prepareCollection()
    {   
        $countries = Mage::getModel('directory/country')->getCollection();
        $collection = new Varien_Data_Collection();
        foreach ($countries as $country) {
            $item = new Varien_Object();
            $item->setCountryId($country->getCountryId());
            $item->setName($country->getName());
            $item->setResult(Mage::Helper('veredus/local')->CheckCountry($country));
            if ($item->getResult() != "") {
                $collection->addItem($item);
            }
        
        }
        $this->setCollection($collection);
        parent::_prepareCollection();        
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('country_id', array(
            'header'=> Mage::helper('veredus')->__('Codice'),
            'align'     => 'right',
            'width'     => '40px',
            'index'     => 'country_id',
        ));

        $this->addColumn('Name', array(
            'header'    => Mage::helper('veredus')->__('Country'),  
            'index'     => 'name',
            'width'     => '200px',
        ));        
        
        $this->addColumn('result', array(
            'header' => Mage::helper('veredus')->__('Descrizione'),
            'index'  => 'result',
            'type'   => 'textarea',
            'width'  => '500px',
        ));

        $this->addExportType('*/*/exportcheck/exp_type/xls/','Excel XML');
        $this->addExportType('*/*/exportcheck/exp_type/csv/', 'CSV');        


        return parent::_prepareColumns();
    }

    
}
?>