<?php


namespace Mageplaza\GiftCard\Block;


class GiftCard extends \Magento\Framework\View\Element\Template
{
    protected $helperData;

    protected $_historyFactory;
    protected $customerSession;
    protected $_timezone;
    protected $_customerFactory;
    protected $_balanceFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Mageplaza\GiftCard\Model\BalanceFactory $balanceFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Mageplaza\GiftCard\Helper\Data $helperData


    )
    {
        $this->_historyFactory = $historyFactory;
        $this->customerSession = $customerSession;
        $this->_balanceFactory = $balanceFactory;
        $this->_timezone = $timezone;
        $this->helperData = $helperData;
        $this->getFormData();
        parent::__construct($context);
    }

    public function getHistoryCollection()
    {
//        $this->_historyFactory->jionGetCode();
        $idCus = $this->customerSession->getCustomerId();
        $history = $this->_historyFactory->create();

        $collection = $history->getCollection();
        $collection->addFilter('customer_id', $idCus);
        return $collection;

    }

    public function format($date)
    {
        return $this->_timezone->date(new \DateTime($date))->format('d/m/Y');
    }

    function formatCurency($curren)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
        $curenHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data'); // Instance of Pricing Helper
        return $curenHelper->currency($curren, true, false);
    }

    protected function getFormData()
    {
        //  $data = $this->getRequest()->getParams('ad');
        //   if($data) return $data;
    }


    function getRedemStatus()
    {
        return $this->helperData->getGeneralConfig('enableRedem');

    }
    function getGiftcardStatus()
    {
        return $this->helperData->getGeneralConfig('enableGiftCard');

    }

    function getBalance()
    {
        $balance = $this->_balanceFactory->create();
        $balance->load($this->customerSession->getCustomerId());
        $balance = $balance->getData();
       if ($balance) return $this->formatCurency($balance['balance']) ; else return 'You have no balance';
    }


}