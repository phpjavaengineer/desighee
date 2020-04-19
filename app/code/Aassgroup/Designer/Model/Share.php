<?php
/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */

namespace Aassgroup\Designer\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Share extends \Magento\Framework\Model\AbstractModel implements IdentityInterface {

    const CACHE_TAG = 'dd_designer_share_block';

    /**
     * @var string
     */
    protected $_cacheTag = 'dd_designer_share_block';

    const IDENTIFIER = 'dd_designer_share_model_';

    /**
     * @return void
     */
    protected function _construct() {
        $this->_init('Aassgroup\Designer\Model\ResourceModel\Share');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities() {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getIdentifier()];
    }

}


