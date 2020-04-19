<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Model\ResourceModel\Cartitem;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    /**
     * @var string
     */
    protected $_idFieldName = '_item_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Aassgroup\Designer\Model\Cartitem', 'Aassgroup\Designer\Model\ResourceModel\Cartitem');
        /* $this->_map['fields']['store'] = 'store_table.store_id'; */
    }

    protected function _beforeLoad() {
        parent::_beforeLoad();
    }

}
