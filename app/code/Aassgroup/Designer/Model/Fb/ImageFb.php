<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Model\Fb;

class ImageFb extends \Aassgroup\Designer\Model\AbstractFb
{
    
    protected $_imageId;


    public function doCall()
    {
        $this->prepareUrl([
            'format' => 'json',
            'fields' => 'images'
        ]);
        $this->initCall();
        $response = $this->call();
        /*
        if ($errno = curl_errno($this->_curl)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
         * 
         */
        return $this->parseResponse($response);
        
    }
    
    public function setImageId($id)
    {
        $this->_imageId = $id;
    }
    
    public function getImageId()
    {
        return $this->_imageId;
    }

    public function prepareUrl(array $params)
    {
        $this->setBaseUrl();
        $this->_url .= '/' . $this->getImageId();
        $params['access_token'] = $this->getToken();
        $this->appendParamsToUrl($params);
    }
    
    public function parseResponse($response)
    {
        $dataImages = json_decode($response);
        if(!empty($dataImages) && !empty($dataImages->images)) {
            foreach($dataImages->images as $image) {
                if($image->width < 1000 and $image->width < 1000){
                    return $image;
                }
            }
        }
    }
}
