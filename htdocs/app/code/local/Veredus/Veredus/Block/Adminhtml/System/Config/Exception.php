<?php
/**
 *
 * Override della classe base per poter gestire una tabella di condifurazoine più strutturata
 *
 * @category    Custom
 * @package     Mage_Adminhtml
 * @author      Marco Mancinelli
 * 
 */

class Veredus_Veredus_Block_Adminhtml_System_Config_Exception extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_validTag = array('select', 'input');

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * override
     *
     * @param string $name
     * @param array $params
     */
    public function addColumn($name, $params)
    {
        $this->_columns[$name] = array(
            'label'     => empty($params['label']) ? 'Column' : $params['label'],
            'size'      => empty($params['size'])  ? false    : $params['size'],
            'style'     => empty($params['style'])  ? null    : $params['style'],
            'class'     => empty($params['class'])  ? null    : $params['class'],
            'type'      => empty($params['type'])   ? null    : $params['type'],
            'tag'       => empty($params['tag'])    ? 'input': $params['tag'],
            'option'    => empty($params['option']) ? null    : $params['option'],
            'renderer'  => false,
        );
        if ((!empty($params['renderer'])) && ($params['renderer'] instanceof Mage_Core_Block_Abstract)) {
            $this->_columns[$name]['renderer'] = $params['renderer'];
        }
    }
    
    /**
     * override
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     */
    public function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }

        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($column['renderer']) {
            return $column['renderer']->setInputName($inputName)->setColumnName($columnName)->setColumn($column)
                ->toHtml();
        }
        $html = 'type="'. $column['type'] . '" name="' . $inputName . '"' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '"'.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '');
        
        switch (strtoupper($column['tag'])) {
            case 'SELECT':
                $html = '<select ' . $html . '>'; 
                if (isset($column['option'])) {
                    foreach ($column['option'] as $k=>$v) {
                        $html .= '<option value="'.$k.'"';
// No!!! il valore della colonna viene assegnato in fase di render
                        if ($k == $columnName) {
                            $html .= 'selected="selected"';
                        }
                        $html .= '>'.$v.'</option>';
                    }
                    $html .= '</select>';
                }
                break;
            case 'TEXTAREA':
                $html = '<textarea ' . $html . '>#{' . $columnName . '}</textarea>';
                break;
            default:
                $html = '<input ' . $html . ' value="#{' . $columnName . '}" ' .'/>';                
                break;
        }

        return $html;

    }
    
}
?>
