<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Controller\Index;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Framework\App\Action\Action{

    protected $_tmpDesignModel;
    protected $_date;
    protected $_ddHelper;
    protected $_logger;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        DateTime $date,
        \Aassgroup\Designer\Model\TmpdesignFactory $tmpDesignModel,
        \Aassgroup\Designer\Helper\Data $ddHelper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_ddHelper        = $ddHelper;
        $this->_tmpDesignModel  = $tmpDesignModel;
        $this->_date            = $date;
        $this->_logger          = $logger;

        parent::__construct($context);
    }

    public function execute() {
        $post = $this->getRequest()->getParams();
        if (!$post || empty($post['json']) || empty($post['data']) || empty($post['conf'] || empty($post['svg']) )
        ) {
            return $this->sendError(__('Not correct data sent!'));
        }
        try {
            $uniqueId = !empty($post['unique_id']) ? $post['unique_id'] : null;
            if(!$uniqueId) {
                $model = $this->_tmpDesignModel->create()
                        ->load(null);
                $model->setCreatedTime($this->_date->gmtDate());
            }else{
                $model = $this->_tmpDesignModel->create()
                        ->load($uniqueId, 'unique_id');
                
                $model->setUpdatedTime($this->_date->gmtDate());
            }

            $model->setJsonText($post['json']);
            $model->setSvgText(json_decode($post['svg']));
            $model->setPngBlob($post['data']);
            $model->setConf($post['conf']);
            $model->setMediaId($post['media_id']);

            $model->save();

            return $this->sendResponse([
                'success' => true,
                'design_id' => $model->getUniqueId() 
            ]);

        } catch (\Exception $ex) {
            return $this->sendError(__('Error') . ': ' . $ex->getMessage());
        }
    }
    
    protected function sendError($errMessage) {
        return $this->sendResponse([
            'error' => true,
            'errMessage' => $errMessage
        ]);
    }
    
    protected function sendResponse($response = array()) {

        $jsonResponse = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $jsonResponse->setData($response);
        return $jsonResponse;
    }

}
