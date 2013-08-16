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
class Veredus_Veredus_Block_Adminhtml_Catalog_Attribute_Edit_Form extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Form {
    
    /**
     * Override per aggiungere l'encrypt per caricare le immagini
     * @return type
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array('id'      => 'edit_form', 
                                           'action'  => $this->getData('action'), 
                                           'method'  => 'post',
                                           'enctype' => 'multipart/form-data'));
        $form->setUseContainer(true);
        $this->setForm($form);
        return Mage_Adminhtml_Block_Widget_Form::_prepareForm();
    }
    
}

?>
