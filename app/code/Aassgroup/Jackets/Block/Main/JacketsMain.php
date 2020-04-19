<?php
/**
 * @Package Module: Aassgroup_Jackets
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Jackets\Block\Main;

use Aassgroup\Designer\Model\CartitemFactory;
use Aassgroup\Designer\Model\OrderFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;


class JacketsMain extends Template
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;
    /**
     * @var OrderFactory
     */
    protected $_designerOrder;
    /**
     * @var CartitemFactory
     */
    protected $_designCartItemModel;
    /**
     * @var ProductFactory
     */
    protected $_productModel;

    /**
     * JacketsMain constructor.
     * @param Context $context
     * @param Registry $registry
     * @param OrderFactory $designerOrder
     * @param CartitemFactory $designCartItemModel
     * @param ProductFactory $productModel
     * @param array $data
     */
    public function __construct
    (
        Context $context,
        Registry $registry,
        OrderFactory $designerOrder,
        CartitemFactory $designCartItemModel,
        ProductFactory $productModel,
        array $data = array()
    )
    {

        $this->_coreRegistry = $registry;
        $this->_designCartItemModel = $designCartItemModel;
        $this->_designerOrder = $designerOrder;
        $this->_productModel = $productModel;

        parent::__construct($context, $data);
    }

    /**
     * @return AbstractCollection
     */
    public function getDesignerData()
    {
        $order = $this->getOrder();
        $collection = $this->_designCartItemModel->create()
            ->getCollection();

        $collection
            ->getSelect()->group('json_text')
            ->where('cart_quote_id=?', $order->getQuoteId()
            );
        return $collection;
    }

}
