<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface {

    protected $_blockFactory;

    public function __construct(
    \Magento\Cms\Model\BlockFactory $blockFactory
    ) {
        $this->_blockFactory = $blockFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {

        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.7') < 0) {
            $this->createCustomizeButtonCmsBlock();
        }
        if (version_compare($context->getVersion(), '1.0.8') < 0) {
            $this->createHelpFirsBlockCmsBlock();
        }
        if (version_compare($context->getVersion(), '1.0.9') < 0) {
            $this->createHelpsBlockCmsBlock();
        }
        if (version_compare($context->getVersion(), '1.1.0') < 0) {
            $this->createDesignerSwitchBlock();
        }
        $setup->endSetup();
    }
    
    protected function createDesignerSwitchBlock() {
        $_block = [
            'title' => 'Designer Help Switch Images Block',
            'identifier' => 'customize-help-switch-images-block-default',
            'stores' => [0],
            'is_active' => 1,
            'content' => '<p>Change images to be designed</p>',
        ];
        $this->_blockFactory->create()->setData($_block)->save();
    }
    
    protected function createHelpsBlockCmsBlock() {
        $_block = [
            'title' => 'Designer Help Second Block',
            'identifier' => 'customize-help-second-block-default',
            'stores' => [0],
            'is_active' => 1,
            'content' => '<p>Use these buttons for <br><strong>print</strong>, <strong>download</strong>, <strong>preview</strong>, <strong>clear all</strong> designed image</p>',
        ];
        $this->_blockFactory->create()->setData($_block)->save();
        $_block = [
            'title' => 'Designer Help Third Block',
            'identifier' => 'customize-help-third-block-default',
            'stores' => [0],
            'is_active' => 1,
            'content' => '<p>Use <strong>"Save"</strong> button after desiign ready.<br><strong>"Close"</strong> button after design done.</p>',
        ];
        $this->_blockFactory->create()->setData($_block)->save();
    }
    
    protected function createHelpFirsBlockCmsBlock() {
        $_block = [
            'title' => 'Designer Help First Block',
            'identifier' => 'customize-help-first-block-default',
            'stores' => [0],
            'is_active' => 1,
            'content' => '<p>Use these buttons for add <br><strong>new elements</strong> to image</p>',
        ];
        $this->_blockFactory->create()->setData($_block)->save();
    }

    protected function createCustomizeButtonCmsBlock() {
        $_block = [
            'title' => 'Designer Help Customize button default',
            'identifier' => 'customize-button-help-default',
            'stores' => [0],
            'is_active' => 1,
            'content' => '<p>Use "<strong>Customize It</strong> button" for create your own custom design</p>',
        ];
        $this->_blockFactory->create()->setData($_block)->save();
    }

}
