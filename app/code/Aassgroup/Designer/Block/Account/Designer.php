<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */
namespace Aassgroup\Designer\Block\Account;

class Designer extends \Magento\Framework\View\Element\Template {

    protected $_coreRegistry;
    protected $_designerOrder;
    protected $_designCartItemModel;
    protected $_productModel;

    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Registry $registry, \Aassgroup\Designer\Model\OrderFactory $designerOrder, \Aassgroup\Designer\Model\CartitemFactory $designCartItemModel, \Magento\Catalog\Model\ProductFactory $productModel, array $data = array()
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $registry;
        $this->_designCartItemModel = $designCartItemModel;
        $this->_designerOrder = $designerOrder;
        $this->_productModel = $productModel;
    }

    public function getOrder() {
        return $this->_coreRegistry->registry('current_order');
    }

    public function prepareConf($confStr) {
        return $confStr ? json_decode($confStr) : [];
    }

    public function getProduct($productId) {
        return $this->_productModel->create()
                        ->load($productId);
    }

    public function getDesignerData() {
        $order = $this->getOrder();
        $collection = $this->_designCartItemModel->create()
                ->getCollection();

        $collection->getSelect()->group('json_text')->where('cart_quote_id=?', $order->getQuoteId());
        return $collection;
    }

}
