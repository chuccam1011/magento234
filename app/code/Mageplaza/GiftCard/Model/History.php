<?php


namespace Mageplaza\GiftCard\Model;


class History extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'giftcard_history';

    protected $_cacheTag = 'giftcard_history';

    protected $_eventPrefix = 'giftcard_history';

    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\ResourceModel\History');
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

    public function getCode($idGift)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
        $tableName = 'mageplaza_giftcard_code';
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        //Select Data from table
        $sql = "Select code FROM " . $tableName . ' WHERE giftcard_id= ' . $idGift;
        $result = $connection->fetchAll($sql); // gives associated array, table fields as key in array.
        //    print_r($result);
        return $result[0]['code'];
    }
}
