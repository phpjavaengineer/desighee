<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RemoveAfter implements ObserverInterface {

    protected $_designCartItemModel;
    
    protected $_designerHelper;
    
    public function __construct(
       \Aassgroup\Designer\Helper\Data $designerHelper,     
       \Aassgroup\Designer\Model\CartitemFactory $designCartItemModel
    ) {
        $this->_designerHelper = $designerHelper;
        $this->_designCartItemModel = $designCartItemModel;
    }

    public function execute(Observer $observer) {
        if(!$this->_designerHelper->getIsDesignerEnabled()) {
            return;
        }
        
        $quoteItem = $observer->getQuoteItem();
        $model = $this->_designCartItemModel->create()
                ->load($quoteItem->getId(), 'cart_item_id');
        
        $model->delete();
    }
    
    

}
