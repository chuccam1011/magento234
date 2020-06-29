<?php


namespace Mageplaza\GiftCard\Observer;


use Magento\Catalog\Model\ProductRepository;
use Magento\Quote\Model\Quote;

class CreateGiftCard implements \Magento\Framework\Event\ObserverInterface
{
    protected $_historyFactory;
    protected $_giftCardFactory;
    protected $helperData;
    protected $helperDataEnable;

    public function __construct(
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Mageplaza\GiftCard\Helper\DataCodeLength $helperData,
        \Mageplaza\GiftCard\Helper\Data $helperDataEnable


    )
    {
        $this->helperData = $helperData;
        $this->helperDataEnable = $helperDataEnable;
        $this->_historyFactory = $historyFactory;
        $this->_giftCardFactory = $giftCardFactory;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        if ($this->helperDataEnable->getGeneralConfig('enableGiftCard') == 1) {

            try {
                $order = $observer->getEvent()->getOrder();
                $quote = $observer->getEvent()->getQuote();
                $incrementId = $order->getIncrementId();
                // if have custom_discount apply it in order
//                $discountOrder = $quote->getData('giftcard_base_discount');
//                if ($discountOrder) {
//                    $this->setUseGiftCardInOrder($order, $discountOrder, $quote);
//                }

                // colect giftcard amount to create
                /** @var Quote $quote */
                $dataItems = $quote->getItems();
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                foreach ($dataItems as $item) {

                    /** @var ProductRepository $product */
                    $product = $objectManager->create(ProductRepository::class);
                    $product = $product->getById($item->getProductId());
                    $amount = $product->getData('gift_card_amount');
                    // $logger->info((json_encode($product->getData())));
                    $qty = (int)$item->getQty();
                    for ($i = 0; $i < $qty; $i++) {
                        if ($amount) {
                            $this->createGiftCard($amount, $incrementId, $order);
                        }
                    }

                }


            } catch (\Exception $e) {
                $logger->info($e->getTraceAsString());
            }
        }
    }

    private function setUseGiftCardInOrder($order, $discountInOrder, $quote)//set data in giftcard table
    {
        //set data in giftcard table
        $giftcard = $this->_giftCardFactory->create();
        $code = $quote->getData('giftcard_code');

        $data = [
            'giftcard_id' => $this->getGiftCardId($code),
            'balance' => $giftcard->getBalance() - $discountInOrder,
            'amount_used' => $giftcard->getAmountUsed() + $discountInOrder
        ];
        $giftcard->setData($data)->save();

        //set data in history giftcard
        $giftcardId = $this->getGiftCardId($code);
        $history = $this->_historyFactory->create();
        $incrementId = $order->getIncrementId();
        $dataHistory = [
            'customer_id' => $order->getCustomerId(),
            'giftcard_id' => $giftcardId,
            'action' => 'Use for Order: ' . $incrementId,
            'amount' => $discountInOrder
        ];
        $history->addData($dataHistory)->save();

    }

    protected function createGiftCard($giftcard_amount, $incrementId, $order)
    {
        if ($giftcard_amount > 0) {

            //create gift card
            $giftcard = $this->_giftCardFactory->create();
            $code = $this->getCodeRandom($this->helperData->getGeneralConfig('codelength'));

            //add data to history table
            $data = [
                'code' => $code,
                'balance' => $giftcard_amount,
                'created_from' => $incrementId
            ];
            $giftcard->addData($data)->save();

            $giftcardId = $this->getGiftCardId($code);     // use code to filter giftcard

            if ($giftcardId) {
                $dataHistory = [
                    'customer_id' => $order->getCustomerId(),
                    'giftcard_id' => $giftcardId,
                    'action' => 'Create from : ' . $incrementId,
                    'amount' => $giftcard_amount
                ];
                $history = $this->_historyFactory->create();
                $history->addData($dataHistory)->save();
            }

        }
    }

    private function getGiftCardId($code)
    {
        $giftcard = $this->_giftCardFactory->create();
        $collection = $giftcard->getCollection();
        $collection->addFilter('code', $code);
        $data = $collection->getData();
        return isset($data[0]) ? $data[0]['giftcard_id'] : null;

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
