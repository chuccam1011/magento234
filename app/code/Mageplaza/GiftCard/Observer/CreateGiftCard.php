<?php


namespace Mageplaza\GiftCard\Observer;


class CreateGiftCard implements \Magento\Framework\Event\ObserverInterface
{
    protected $logger;
    protected $_historyFactory;
    protected $_giftCardFactory;
    protected $helperData;

    public function __construct(
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Mageplaza\GiftCard\Helper\DataCodeLength $helperData

    )
    {
        $this->helperData = $helperData;
        $this->_historyFactory = $historyFactory;
        $this->_giftCardFactory = $giftCardFactory;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $quote = $observer->getEvent()->getQuote();
            $incrementId = $order->getIncrementId();

            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info("________");
            $logger->info(json_encode($quote->getItemsCollection()->getData()));

            if ($incrementId) {

                //create gift card
                $giftcard = $this->_giftCardFactory->create();
                $code = $this->getCodeRandom($this->helperData->getGeneralConfig('codelength'));
                $data = [
                    'code' => $code,
                    'balance' => $order->getBaseGrandTotal(),
                    'created_from' => $incrementId
                ];
                $giftcard->addData($data)->save();

                //add data to history table
                $giftcardId = $this->getGiftCardId($code);     // use code to filter giftcard
                // $logger->info('Gift ID : '.$giftcardId);

                if ($giftcardId) {
                    $dataHistory = [
                        'customer_id' => $order->getCustomerId(),
                        'giftcard_id' => $giftcardId,
                        'action' => 'Create from : ' . $incrementId,
                        'amount' => $order->getSubTotal()
                    ];
                    $history = $this->_historyFactory->create();
                    $history->addData($dataHistory)->save();
                }

            }
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }

    }

    private function getGiftCardId($code)
    {
        $giftcard = $this->_giftCardFactory->create();
        $collection = $giftcard->getCollection();
        $collection->addFilter('code', $code);
        $data = $collection->getData();
        return $data[0]['giftcard_id'];

    }

    private function getCodeRandom($n)
    {
        $characters = 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789';
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
