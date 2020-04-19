<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    const DESIGNER_GENERAL_ENABLED          = 'develo_productdesigner/general/enable';
    const DESIGNER_GENERAL_ENABLEPDF        = 'develo_productdesigner/general/enablepdf';
    const DESIGNER_GENERAL_PDFPATH          = 'develo_productdesigner/general/pdfsavepath';
    const DESIGNER_GENERAL_PRINTTYPES       = 'develo_productdesigner/general/printtypes';
    const DESIGNER_GENERAL_FRONT_ROUTE      = 'develo_productdesigner/general/product_designer_route';

    const DESIGNER_FRONT_ENABLE_FOR_ALL     = 'develo_productdesigner/frontend/enable_all';
    const DESIGNER_FRONT_PRODUCT_ATTR_SETS  = 'develo_productdesigner/frontend/attributes_sets';
    const DESIGNER_FRONT_ENABLE_ADD_IMAGE   = 'develo_productdesigner/frontend/enable_add_image';
    const DESIGNER_FRONT_ENABLE_ADD_TEXT    = 'develo_productdesigner/frontend/enable_add_text';
    const DESIGNER_FRONT_ENABLE_ADD_FROM_LIB = 'develo_productdesigner/frontend/enable_add_from_library';
    /* const DESIGNER_FRONT_PRODUCT_LAYER_POSITION = 'develo_productdesigner/frontend/layer_position'; */
    const DESIGNER_FRONT_PRODUCT_GOOGLE_FONTS = 'develo_productdesigner/frontend/google_fonts';
    const DESIGNER_FRONT_PRODUCT_MULTIPLE_ATTRIBUTE = 'develo_productdesigner/frontend/multiple_addtocart_attribute';

    const DESIGNER_PRICE_LAYER_IMAGE        = 'develo_productdesigner/prices/layer_image_price';
    const DESIGNER_PRICE_LAYER_TEXT         = 'develo_productdesigner/prices/layer_text_price';
    
    const DESIGNER_HELP_CUSTOMIZE_BUTTON    = 'develo_productdesigner/help/customize_button_block';
    const DESIGNER_HELP_FIRST_BLOCK         = 'develo_productdesigner/help/designer_control_main_block';
    const DESIGNER_HELP_SECOND_BLOCK        = 'develo_productdesigner/help/designer_control_second_block';
    const DESIGNER_HELP_THIRD_BLOCK         = 'develo_productdesigner/help/designer_control_third_block';
    const DESIGNER_HELP_SWITCH_BLOCK        = 'develo_productdesigner/help/designer_control_switch_images_block';
    
    const DESIGNER_SOCIAL_SHARE_PINTEREST   = 'develo_productdesigner/social/enable_pinterest_share';
    const DESIGNER_SOCIAL_SHARE_TWITTER     = 'develo_productdesigner/social/enable_twitter_share';
    const DESIGNER_SOCIAL_SHARE_FACEBOOK    = 'develo_productdesigner/social/enable_facebook_share';
    const DESIGNER_SOCIAL_FACEBOOK_IMPORT   = 'develo_productdesigner/social/enable_facebook_my_photos';
    const DESIGNER_SOCIAL_FACEBOOK_APP_ID   = 'develo_productdesigner/social/facebook_app_id';
    const DESIGNER_SOCIAL_FACEBOOK_APP_SECRET  = 'develo_productdesigner/social/facebook_app_secret';
    const DESIGNER_SOCIAL_INSTAGRAM_IMPORT  = 'develo_productdesigner/social/enable_instagram_my_photos';
    const DESIGNER_SOCIAL_INSTAGRAM_CLIENT_ID = 'develo_productdesigner/social/instagram_client_id';
    const DESIGNER_SOCIAL_INSTAGRAM_SECRET  = 'develo_productdesigner/social/instagram_client_secret';

    protected $_fileFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        $this->_fileFactory = $fileFactory;
        parent::__construct($context);
    }

    public function getIsDesignerEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_GENERAL_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getEnablePdfs() {
        if(!$this->getIsDesignerEnabled()){ return false; }
        return $this->scopeConfig->getValue(self::DESIGNER_GENERAL_ENABLEPDF , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getPdfSavePath() {
        $defaultPath = 'dd_pdfs';
        $customPath = $this->scopeConfig->getValue(self::DESIGNER_GENERAL_PDFPATH , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return  (!empty($customPath)) ? $customPath : $defaultPath;
    }

    public function getPrintTypes() {
        if(!$this->getIsDesignerEnabled()){ return false; }
        $value = $this->scopeConfig->getValue(self::DESIGNER_GENERAL_PRINTTYPES , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return ($value) ? explode(',',$value) : array('vinyl');
    }


    public function getInstagramClientSecret() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_INSTAGRAM_SECRET , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getInstagramClientId(){
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_INSTAGRAM_CLIENT_ID , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getIsInstagramImportEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_INSTAGRAM_IMPORT , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getFbAppSecret() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_FACEBOOK_APP_SECRET, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getFbAppId() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_FACEBOOK_APP_ID , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getIsFbImportEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_FACEBOOK_IMPORT , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getIsFbEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_SHARE_FACEBOOK , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getIsTwitterShareEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_SHARE_TWITTER , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getIsPinterestShareEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_SHARE_PINTEREST , \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getIsInstagramEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_SOCIAL_SHARE_INSTAGRAM, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getHelpSwitchBlock() {
        return $this->scopeConfig->getValue(self::DESIGNER_HELP_SWITCH_BLOCK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getHelpThirdBlock() {
        return $this->scopeConfig->getValue(self::DESIGNER_HELP_THIRD_BLOCK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getHelpSecondBlock() {
        return $this->scopeConfig->getValue(self::DESIGNER_HELP_SECOND_BLOCK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getHelpFirstBlock() {
        return $this->scopeConfig->getValue(self::DESIGNER_HELP_FIRST_BLOCK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getHelpCustomizeButton() {
        return $this->scopeConfig->getValue(self::DESIGNER_HELP_CUSTOMIZE_BUTTON, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getIsAddImageEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_FRONT_ENABLE_ADD_IMAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getIsAddTextEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_FRONT_ENABLE_ADD_TEXT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getIsAddFromLibraryEnabled() {
        return $this->scopeConfig->getValue(self::DESIGNER_FRONT_ENABLE_ADD_FROM_LIB, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getMultipleAddtocartAttribute() {
        if(!$this->getIsDesignerEnabled()){ return false; }
        return $this->scopeConfig->getValue(self::DESIGNER_FRONT_PRODUCT_MULTIPLE_ATTRIBUTE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function disableQuantityField(){
        return ($this->getMultipleAddtocartAttribute());
    }

    public function shouldRenderQuantity(){
        return (!$this->getMultipleAddtocartAttribute());
    }


    public function getDesignerFrontEndRoute() {
        return $this->scopeConfig->getValue(self::DESIGNER_GENERAL_FRONT_ROUTE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getIsEnabledForAllProducts() {
        return $this->scopeConfig->getValue(self::DESIGNER_FRONT_ENABLE_FOR_ALL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getProductsAttributesSets() {
        return $this->scopeConfig->getValue(self::DESIGNER_FRONT_PRODUCT_ATTR_SETS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getGoogleFonts() {
        return $this->scopeConfig->getValue(self::DESIGNER_FRONT_PRODUCT_GOOGLE_FONTS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getLayerImgPrice() {
        return $this->scopeConfig->getValue(self::DESIGNER_PRICE_LAYER_IMAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getLayerTextPrice() {
        return $this->scopeConfig->getValue(self::DESIGNER_PRICE_LAYER_TEXT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getProductsAttributesSetsArray() {
        $attributeSets = $this->getProductsAttributesSets();

        if ($attributeSets) {
            return explode(',', $attributeSets);
        }
        return array();
    }

    public function getIsActiveOnProductView($product) {
        if (!$this->getIsDesignerEnabled()) {
            return false;
        }
        if ($this->getIsEnabledForAllProducts()) {
            return true;
        }
        $attributesSets = $this->getProductsAttributesSetsArray();
        if ($attributesSets && in_array($product->getAttributeSetId(), $attributesSets)) {
            return true;
        }
    }

}
