<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\App\Filesystem\DirectoryList;

class UpgradeSchema implements UpgradeSchemaInterface
{

    const LONG_BLOB_TYPE = 'longblob';

    /**
     * @var Magento\Framework\Filesystem\Io\File
     */
    protected $_io;

    /**
     * @var Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        EavSetupFactory $eavSetupFactory,
        File $io,
        DirectoryList $directoryList
    )
    {
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->_io = $io;
        $this->_directoryList = $directoryList;
    }

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->initFieldGroupUid($setup);
            $this->initFieldImageSrc($setup);
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $this->initTmpDesignTable($setup);
            $this->initDesignCartItemsTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $this->initFeildProductId($setup);
        }

        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $this->initOrderTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            $this->initExtraConfField($setup);
        }

        if (version_compare($context->getVersion(), '1.0.6') < 0) {
            $this->initMediaIdField($setup);
        }

        if (version_compare($context->getVersion(), '1.1.1') < 0) {
            $this->changeColumnToLongBlob($setup);
        }

        if (version_compare($context->getVersion(), '1.2.1') < 0) {
            $this->createShareTable($setup);
        }

        if (version_compare($context->getVersion(), '1.2.3') < 0) {
            $this->createMediaDirectory($setup);
        }

        if (version_compare($context->getVersion(), '1.2.4') < 0) {
            $this->addSvgColumn($setup);
        }

        if (version_compare($context->getVersion(), '1.2.5') < 0) {
            //$this->addPrintType($setup);???
        }

        if (version_compare($context->getVersion(), '1.2.6') < 0) {
            $this->addDesignIdQuoteItemField($setup);
        }

        $setup->endSetup();
    }


    protected function addDesignIdQuoteItemField($setup) {
      $setup->getConnection()->addColumn(
              $setup->getTable('quote_item'), 'design_id', [
          'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          'nullable' => true,
          'comment' => 'Aassgroup_Designer Unique ID'
              ]
      );
    }

    protected function createMediaDirectory($setup){

        // Designer assets directory
        if(!file_exists($this->_directoryList->getPath('media'). '/dd_library/default')) {
            $this->_io->mkdir($this->_directoryList->getPath('media') . '/dd_library/default', 0755);
        }

        //Designer design PDF's directory
        if(!file_exists($this->_directoryList->getPath('media'). '/dd_pdfs')) {
            $this->_io->mkdir($this->_directoryList->getPath('media') . '/dd_pdfs/tmp', 0755);
        }

    }

    protected function createShareTable($setup)
    {

        $table_share = $setup->getConnection()
                ->newTable($setup->getTable('dd_productdesigner_share'))
                ->addColumn(
                    'share_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true, 'autoincrement' => true], 'Autoincrement Id'
                )
                ->addColumn(
                    'share_unique_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false], 'Share Image Id'
                )
                ->addColumn(
                    'system_product_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => false], 'Magento product Id'
                )
                ->addColumn(
                    'share_config', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => false], 'Configuration JSON for share image'
                )
                ->addColumn(
                    'share_url', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['default' => null, 'nullable' => true], 'PNG Image url'
                )
                ->addColumn(
                    'share_url_full', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['default' => null, 'nullable' => true], 'Full Share url'
                )
                ->addColumn(
                    'created_time', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null, ['nullable' => true, 'default' => null], 'Time of creation'
                )

        ;
        $setup->getConnection()->createTable($table_share);
    }

    protected function changeColumnToLongBlob($setup)
    {
        $setup->getConnection()->changeColumn(
                $setup->getTable('dd_productdesigner_tmp_designs'), 'png_blob', 'png_blob', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BLOB,
            'length' => 10485760,
                ]
        );
        $setup->getConnection()->changeColumn(
                $setup->getTable('dd_productdesigner_cart_items'), 'png_blob', 'png_blob', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BLOB,
            'length' => 10485760,
                ]
        );
    }

    protected function addSvgColumn($setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('dd_productdesigner_tmp_designs'), 'svg_text', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => false,
                'comment' => 'Canvas SVG data'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('dd_productdesigner_cart_items'), 'svg_text', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => false,
                'comment' => 'Canvas SVG data'
            ]
        );
    }

    protected function addPrintType($setup)
    {
       //???
    }

    protected function initMediaIdField($setup)
    {
        $setup->getConnection()->addColumn(
                $setup->getTable('dd_productdesigner_tmp_designs'), 'media_id', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            'nullable' => true,
            'comment' => 'Media ID magento field'
                ]
        );
    }

    protected function initExtraConfField($setup)
    {
        $setup->getConnection()->addColumn(
                $setup->getTable('dd_productdesigner_image'), 'extra_config', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'nullable' => true,
            'comment' => 'Extra Configuration'
                ]
        );
    }

    protected function initOrderTable($setup)
    {
        $table_orders = $setup->getConnection()
                ->newTable($setup->getTable('dd_productdesigner_order'))
                ->addColumn(
                        'item_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true, 'autoincrement' => true], 'Id'
                )
                ->addColumn(
                'magento_order_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => true], 'Magento Order Id'
                )

        ;
        $setup->getConnection()->createTable($table_orders);
    }

    protected function initFeildProductId($setup)
    {
        $setup->getConnection()->addColumn(
                $setup->getTable('dd_productdesigner_cart_items'), 'magento_product_id', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            'nullable' => true,
            'comment' => 'Magento product ID'
                ]
        );
    }

    protected function initTmpDesignTable($setup)
    {

        $table_tmp_designs = $setup->getConnection()
                ->newTable($setup->getTable('dd_productdesigner_tmp_designs'))
                ->addColumn(
                        'design_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true, 'autoincrement' => true], 'Id'
                )
                ->addColumn(
                        'created_time', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null, ['nullable' => true, 'default' => null], 'Time of creation'
                )
                ->addColumn(
                        'updated_time', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null, ['nullable' => true, 'default' => null], 'Time of last update'
                )
                ->addColumn(
                        'unique_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['default' => null, 'nullable' => true], 'Unique id of design'
                )
                ->addColumn(
                        'json_text', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['default' => null, 'nullable' => true], 'Fabricjs toJson output'
                )
                ->addColumn(
                        'png_blob', \Magento\Framework\DB\Ddl\Table::TYPE_BLOB, null, ['default' => null, 'nullable' => true], 'PNG Image'
                )
                ->addColumn(
                'conf', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['default' => null, 'nullable' => true], 'DD Designer layers configuration'
                )

        ;

        $setup->getConnection()->createTable($table_tmp_designs);
    }

    protected function initDesignCartItemsTable($setup)
    {

        $table_design_cart_items = $setup->getConnection()
                ->newTable($setup->getTable('dd_productdesigner_cart_items'))
                ->addColumn(
                        '_item_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true, 'autoincrement' => true], 'Id'
                )
                ->addColumn(
                        'cart_quote_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['default' => 0, 'nullable' => false], 'Magento Quote Id'
                )
                ->addColumn(
                        'cart_item_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['default' => 0, 'nullable' => false], 'Magento Quote Cart Item Id'
                )
                ->addColumn(
                        'old_cart_quote_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['default' => 0, 'nullable' => false], 'Magento Old Quote Id after quote_merge'
                )
                ->addColumn(
                        'old_cart_item_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['default' => 0, 'nullable' => false], 'Magento Old Quote Cart Item Id after quote_merge'
                )
                ->addColumn(
                        'created_time', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null, ['nullable' => true, 'default' => null], 'Time of creation'
                )
                ->addColumn(
                        'updated_time', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null, ['nullable' => true, 'default' => null], 'Time of last update'
                )
                ->addColumn(
                        'json_text', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['default' => null, 'nullable' => true], 'Fabricjs toJson output'
                )
                ->addColumn(
                        'png_blob', \Magento\Framework\DB\Ddl\Table::TYPE_BLOB, null, ['default' => null, 'nullable' => true], 'PNG Image'
                )
                ->addColumn(
                'conf', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['default' => null, 'nullable' => true], 'DD Designer layers configuration'
                )
        ;

        $setup->getConnection()->createTable($table_design_cart_items);
    }

    protected function initFieldGroupUid($setup)
    {
        $setup->getConnection()->addColumn(
                $setup->getTable('dd_productdesigner_image_groups'), 'group_uid', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'nullable' => true,
            'comment' => 'Group unique ID'
                ]
        );
    }

    protected function initFieldImageSrc($setup)
    {
        $setup->getConnection()->addColumn(
                $setup->getTable('dd_productdesigner_image'), 'image_src', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'nullable' => true,
            'comment' => 'Magento product image source'
                ]
        );
    }

}
