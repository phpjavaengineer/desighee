<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Model\Fb;

class AlbumsFb extends \Aassgroup\Designer\Model\AbstractFb
{

    const URL_ADDON = '/me/albums';

    public function doCall()
    {   
        $this->prepareUrl([
            'format' => 'json',
            'fields' => 'id'
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

    public function prepareUrl(array $params)
    {
        $this->setBaseUrl();
        $this->_url .= self::URL_ADDON;
        $params['access_token'] = $this->getToken();
        $this->appendParamsToUrl($params);
    }

    public function parseResponse($response)
    {
        return json_decode($response);
    }

}
