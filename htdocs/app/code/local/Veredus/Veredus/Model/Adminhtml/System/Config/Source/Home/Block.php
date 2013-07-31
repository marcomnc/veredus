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
class Veredus_Veredus_Model_Adminhtml_System_Config_Source_Home_Block {
    
    public function getCommentText($element, $currentValue) {       
        $html = "";
        if (property_exists($element->comment, "imgname")) {
            $img = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . trim($element->comment->imgname);
            $html .= "<a href=\"$img\" target=\"BLANK\" >";
            $html .= "<img src=\"$img\" width=\"42\" heigth=\"36\" />";
            $html .= "</a>";
            
        }
        if (property_exists($element->comment, "text")) {
            $html .= "<p>" . Mage::Helper('veredus')->__($element->comment->text) . "</p>";
        }
        return $html;
    }
}

?>
