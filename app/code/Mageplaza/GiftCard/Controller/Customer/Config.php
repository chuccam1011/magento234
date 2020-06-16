<?php

namespace Mageplaza\GiftCard\Controller\Customer;

class Config extends \Magento\Framework\App\Action\Action
{

    protected $helperData;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mageplaza\GiftCard\Helper\Data $helperData

    )
    {
        $this->helperData = $helperData;
        return parent::__construct($context);
    }

    public function execute()
    {
        echo $this->helperData->getGeneralConfig('enableGiftCard');
        echo $this->helperData->getGeneralConfig('enableRedem');
        echo $this->helperData->getGeneralConfig('enableUsedCheckOut');

    }
}
