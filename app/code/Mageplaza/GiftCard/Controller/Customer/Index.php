<?php


namespace Mageplaza\GiftCard\Controller\Customer;


class Index extends \Magento\Framework\App\Action\Action
{


    protected $_historyFactory;
    protected $_customerSession;
    protected $_customerFactory;
    protected $_balanceFactory;
    protected $_giftCardFactory;
    protected $_pageFactory;

    protected $helperData;//get config in Admin

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Mageplaza\GiftCard\Model\BalanceFactory $balanceFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Magento\Framework\View\Result\PageFactory $pageFactory


    )
    {
        $this->_historyFactory = $historyFactory;
        $this->_customerSession = $customerSession;
        $this->_balanceFactory = $balanceFactory;
        $this->_giftCardFactory = $giftCardFactory;
        $this->helperData = $helperData;
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $idCus = $this->_customerSession->getCustomerId();
        if (!$idCus) {
            $this->_redirect('customer/account/login/');
        }
        if ($this->helperData->getGeneralConfig('enableGiftCard') == 0) {
            $this->_redirect('customer/account/index');
        }
//        $this->_view->loadLayout();
//        $this->_view->renderLayout();
        $this->checkIssetCustomer($idCus);
        $dataRequest = $this->getRequest()->getParams();
        $history = $this->_historyFactory->create();
        $balance = $this->_balanceFactory->create();
        $giftcard = $this->_giftCardFactory->create();
        $colectionGift = $giftcard->getCollection();

        if ($dataRequest && $this->helperData->getGeneralConfig('enableRedem') != 0) {
            $colectionGift->addFilter('code', $dataRequest['code'])->getFirstItem();
            $data = $colectionGift->getData();
            $data = $data[0];
            if ($colectionGift && isset($data['balance']) && $data['balance'] > $data['amount_used']) {

                //add data to gift card table
                $giftcard->load($data['giftcard_id']);
                $valueBalance = $giftcard->getData('amount_used') + $data['balance'];
                $dataSet = [
                    'giftcard_id' => $data['giftcard_id'],
                    'amount_used' => $valueBalance
                ];
                $giftcard->setData($dataSet)->save();

                //add data to history table
                $dataHistory = [
                    'customer_id' => $this->_customerSession->getCustomerId(),
                    'giftcard_id' => $data['giftcard_id'],
                    'action' => 'Redeem',
                    'amount' => $data['balance']
                ];
                $history->addData($dataHistory)->save();

                //add sub balance to banlance table
                $balance->load($this->_customerSession->getCustomerId());
                $balanceValue = $balance->getData('balance') + $data['balance'];
                $dataBalance = [
                    'customer_id' => $this->_customerSession->getCustomerId(),
                    'balance' => $balanceValue
                ];
                $balance->setData($dataBalance)->save();

                //show alert
                $dataresult = $this->getRequest()->getParams();
                $alert = 'Your Code has been apply :  ' . $dataresult['code'];
                $this->messageManager->addSuccessMessage($alert);
            } else {
                if ($data && $data['balance'] = $data['amount_used']) {
                    $dataresult = $this->getRequest()->getParams();
                    $alert = 'Your Code have no value :  ' . $dataresult['code'];
                    $this->messageManager->addErrorMessage($alert);
                } else {
                    $dataresult = $this->getRequest()->getParams();
                    $alert = 'Can not find your Code :  ' . $dataresult['code'];
                    $this->messageManager->addErrorMessage($alert);
                }

            }
        }
        return $this->_pageFactory->create();

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
