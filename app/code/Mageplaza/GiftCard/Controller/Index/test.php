<?php


namespace Mageplaza\GiftCard\Controller\Index;


class test extends \Magento\Framework\App\Action\Action
{

    protected $_checkoutSession;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Action\Context $context
    )
    {

        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context);
    }


    public function execute()
    {
        $this->_checkoutSession->setStepDa;
        var_dump($this->_checkoutSession->getData());
    }
}
