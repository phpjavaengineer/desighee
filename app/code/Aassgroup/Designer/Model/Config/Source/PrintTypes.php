<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Model\Config\Source;

class PrintTypes implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {
        return array(
            array('value'=>'vinyl','label'=>__('Vinyl')),
            array('value'=>'embroidery','label'=>__('Embroidery'))
        );
    }
}
