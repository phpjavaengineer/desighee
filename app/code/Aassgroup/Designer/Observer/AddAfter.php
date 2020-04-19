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

class AddAfter implements ObserverInterface {

    protected $_productRepo;
    protected $_registry;
    protected $_tmpDesignModel;
    protected $_designerImage;
    protected $_storeManager;
    protected $_designerHelper;
    protected $_currency;
    protected $_imageDesign;
    protected $_request;
    protected $_logger;

    const CURRENT_REGISTRATED_PRODUCT_DESIGNS = 'dd_designer_added_to_cart_designs';

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepo,
        \Aassgroup\Designer\Helper\Data $designerHelper,
        \Aassgroup\Designer\Model\TmpdesignFactory $tmpDesignModel,
        \Aassgroup\Designer\Model\ImageFactory $imageDesign,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\Currency $currency,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Registry $registry,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_productRepo = $productRepo;
        $this->_registry = $registry;
        $this->_designerHelper = $designerHelper;
        $this->_logger = $logger;
        $this->_tmpDesignModel = $tmpDesignModel;
        $this->_currency = $currency;
        $this->_storeManager = $storeManager;
        $this->_imageDesign = $imageDesign;
        $this->_request = $request;
    }

    public function execute(Observer $observer) {

        $designsIds = $this->_request->getParam('dd_design',null);
        if(!$this->_designerHelper->getIsDesignerEnabled() || empty($designsIds)) {
            return;
        }

        $designsIdsStr = implode(',', $designsIds);

        $quoteItem = $observer->getQuoteItem();
        $quoteItem->setDesignId($designsIdsStr);
        if(!$this->_registry->registry(self::CURRENT_REGISTRATED_PRODUCT_DESIGNS)) {
          $this->_registry->register(self::CURRENT_REGISTRATED_PRODUCT_DESIGNS, $designsIdsStr);
        }

        $this->updatePrice($observer,$designsIds);
    }

    protected function updatePrice($observer,$designsIds) {

        $priceUpdate = 0;

        foreach ($designsIds as $designsId) {
            $tmpDesign = $this->_tmpDesignModel->create()
                    ->load($designsId, 'unique_id');

            if (!$tmpDesign->getId()) {
                $this->_logger->error('tmpdesign id not found');
                continue;
            }

            $conf = json_decode($tmpDesign->getConf());
            $mediaId = $tmpDesign->getMediaId();
            $priceUpdate += $this->getPriceByConf($conf, $mediaId);

        }
        if ($priceUpdate > 0) {
            $this->changeItemQuotePrice($observer, $priceUpdate);
        }
    }

    protected function getPriceByConf($conf, $mediaId) {
        $price = 0;
        $extraConf = $this->loadImageExtraConfiguration($mediaId);
        foreach($conf as $_obj) {
            if($_obj->type === 'image' || !empty($_obj->isSvg)) {
                $price += $this->getLayerImgPrice($extraConf);
            }
            if($_obj->type === 'text' || $_obj->type === 'i-text') {
                $price += $this->getLayerTxtPrice($extraConf);
            }
        }

        return $price;
    }

    protected function loadImageExtraConfiguration($mediaId) {
        $model = $this->_imageDesign->create()
                ->load($mediaId, 'media_id');

        $extraConfigStr = $model->getExtraConfig();
        return ($extraConfigStr ? json_decode($extraConfigStr) : []);
    }

    protected function changeItemQuotePrice($observer, $changePrice) {

        $item = $observer->getEvent()->getData('quote_item');
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );

        $price = $item->getProduct()->getFinalPrice() + $changePrice;
        $item->setCustomPrice($price);
        $item->setOriginalCustomPrice($price);
        $item->getProduct()->setIsSuperMode(true);

    }

    protected function convertPrice($basePrice = null) {
        if (is_null($basePrice)) {
            return 0;
        }
        $rate = $this->_storeManager->getStore()->getCurrentCurrencyRate();
        return $rate * $basePrice;
    }

    protected function getLayerImgPrice($extraConf = []) {
        if (key_exists('layer_img_price', $extraConf)) {
            return $this->convertPrice($extraConf->layer_img_price);
        }
        return $this->convertPrice($this->_designerHelper->getLayerImgPrice());
    }

    protected function getLayerTxtPrice($extraConf = []) {
        if (key_exists('layer_txt_price', $extraConf)) {
            return $this->convertPrice($extraConf->layer_txt_price);
        }
        return $this->convertPrice($this->_designerHelper->getLayerTextPrice());
    }

    protected function getCurrencyCode() {
        return $this->_currency->getCurrencySymbol();
    }

}
