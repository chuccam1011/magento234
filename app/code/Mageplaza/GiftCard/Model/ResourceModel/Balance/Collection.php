<?php


namespace Mageplaza\GiftCard\Model\ResourceModel\Balance;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'customer_id';
    protected $_eventPrefix = 'giftcard_customer_balance_collection';
    protected $_eventObject = 'giftcard_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\Balance', 'Mageplaza\GiftCard\Model\ResourceModel\Balance');
    }

}
