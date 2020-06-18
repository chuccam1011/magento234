<?php


namespace Mageplaza\GiftCard\Controller\Customer;


class Index extends \Magento\Framework\App\Action\Action
{


    protected $_historyFactory;
    protected $_customerSession;
    protected $_customerFactory;
    protected $_balanceFactory;
    protected $_giftCardFactory;
    protected $helperData;//get config in Admin

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Mageplaza\GiftCard\Model\BalanceFactory $balanceFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\GiftCard\Helper\Data $helperData

    )
    {
        $this->_historyFactory = $historyFactory;
        $this->_customerSession = $customerSession;
        $this->_balanceFactory = $balanceFactory;
        $this->_giftCardFactory = $giftCardFactory;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->checkIssetCustomer($this->_customerSession->getCustomerId());
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        if ($this->helperData->getGeneralConfig('enableGiftCard') == 0) {
            $this->_redirect('customer/account/');
        };


        $dataRequest = $this->getRequest()->getParams();
        $history = $this->_historyFactory->create();
        $balance = $this->_balanceFactory->create();
        $giftcard = $this->_giftCardFactory->create();
        // echo $data['code'];
        $colectionGift = $giftcard->getCollection();
        if ($dataRequest) {
            $colectionGift->addFilter('code', $dataRequest['code']);
            $data = $colectionGift->getData();
            if ($colectionGift && isset($data[0]['balance']) && $data[0]['balance'] > 0) {//balance must be lager than 0

                //add data to gift card table
                $giftcard->load($data[0]['giftcard_id']);
                $valueBalance = $giftcard->getData('amount_used') + $data[0]['balance'];
                $dataSet = [
                    'giftcard_id' => $data[0]['giftcard_id'],
                    'amount_used' => $valueBalance,
                    'balance' => 0
                ];
                $giftcard->setData($dataSet)->save();

                //add data to history table
                $dataHistory = [
                    'customer_id' => $this->_customerSession->getCustomerId(),
                    'giftcard_id' => $data[0]['giftcard_id'],
                    'action' => 'Redeem',
                    'amount' => $data[0]['balance']
                ];
                $history->addData($dataHistory)->save();

                //add sub balance to banlance table
                $balance->load($this->_customerSession->getCustomerId());
                $balanceValue = $balance->getData('balance') + $data[0]['balance'];
                $this->checkIssetCustomer($this->_customerSession->getCustomerId());
                $dataBalance = [
                    'customer_id' => $this->_customerSession->getCustomerId(),
                    'balance' => $balanceValue
                ];
                $balance->setData($dataBalance)->save();
                //show alert
                $datareuslt = $this->getRequest()->getParams();
                $alert = 'Your Code has been apply :  ' . $datareuslt['code'];
                $this->messageManager->addSuccessMessage($alert);
            } else {
                if ($data && $data[0]['balance'] <= 0) {
                    $datareuslt = $this->getRequest()->getParams();
                    $alert = 'Your Code have no value :  ' . $datareuslt['code'];
                    $this->messageManager->addErrorMessage($alert);
                } else {
                    $datareuslt = $this->getRequest()->getParams();
                    $alert = 'Can not find your Code :  ' . $datareuslt['code'];
                    $this->messageManager->addErrorMessage($alert);
                }

            }
        }
    }

    function checkIssetCustomer($idCustomer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
        $tableName = 'giftcard_customer_balance';
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        //Select Data from table
        $sql = "Select 	balance FROM " . $tableName . ' WHERE customer_id = ' . $idCustomer;
        $result = $connection->fetchAll($sql); // gives associated array, table fields as key in array.
        //    print_r($result);
        if (!$result) {
            $sql = "INSERT INTO " . $tableName . "(`customer_id`, `balance`) VALUES " . "(" . $idCustomer . ",NULL )";
            $connection->query($sql);
        }

    }

}
