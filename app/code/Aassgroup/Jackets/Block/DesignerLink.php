<?php
/**
 * @Package Module: Aassgroup_Jackets
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */
namespace Aassgroup\Jackets\Block;

class DesignerLink extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Render block HTML.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
          //  return parent::_toHtml();
        }
        return '<li><a style="color:yellow" ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a></li>';
    }
}