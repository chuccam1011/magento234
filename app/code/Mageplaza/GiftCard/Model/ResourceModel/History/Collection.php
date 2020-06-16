<?php


namespace Mageplaza\GiftCard\Model\ResourceModel\History;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'history_id';
    protected $_eventPrefix = 'giftcard_history_collection';
    protected $_eventObject = 'history_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\History', 'Mageplaza\GiftCard\Model\ResourceModel\History');
    }
    protected function jionGetCode()
    {
        $history_tbl = "giftcard_history";
        $giftcode_tbl= $this->getTable("mageplaza_giftcard_code");
        $this->getSelect()
            ->join(array('gift' =>$giftcode_tbl),
                $history_tbl . '.giftcard_id= gift.giftcard_id',
                array(
                    'code'
                )
            );
        //  $this->getSelect()->where("payment_method=".$payment_method);
    }
}
