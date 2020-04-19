<?php
/**
 * @Package Module: Aassgroup_Jackets
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Jackets\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

class Customize extends Action
{

    /**
     * @var Filesystem
     */
    protected $_filesystem;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * Customize constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        PageFactory $pageFactory
    ) {

        $this->_filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
//        $this->_view->loadLayout();
//        $this->_view->renderLayout();

        return $this->_pageFactory->create();
    }

    /**
     * @param array $response
     * @return ResultInterface
     */
    protected function sendResponse($response = [])
    {
        $jsonResponse = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $jsonResponse->setData($response);
        return $jsonResponse;
    }
}
