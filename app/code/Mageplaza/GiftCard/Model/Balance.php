<?php


namespace Mageplaza\GiftCard\Model;

class Balance extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'giftcard_customer_balance';

    protected $_cacheTag = 'giftcard_customer_balance';

    protected $_eventPrefix = 'giftcard_customer_balance';

    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\ResourceModel\Balance');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

}
