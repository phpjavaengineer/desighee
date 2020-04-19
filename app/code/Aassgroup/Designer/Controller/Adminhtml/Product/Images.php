<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Controller\Adminhtml\Product;

use Magento\Framework\Controller\ResultFactory;

class Images extends \Magento\Backend\App\Action {
    
    protected $_designer;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Aassgroup\Designer\Model\Designer $designer   
    ) {
        parent::__construct($context);
        $this->_designer = $designer;
    }
    
    public function execute() {
        
        $productId = $this->getRequest()->getParam('product_id');
        return $this->sendResponse([
            'success' => true,
            'data' => $this->_designer->loadConfguration($productId)
        ]);
    }
    
    
    public function sendResponse($response = array()) {
        $jsonResponse = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $jsonResponse->setData($response);
        return $jsonResponse;
    }
}
