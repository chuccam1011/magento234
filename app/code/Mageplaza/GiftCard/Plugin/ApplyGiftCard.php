<?php


namespace Mageplaza\GiftCard\Plugin;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class ApplyGiftCard extends Action

{
    protected $_giftCardFactory;
    protected $_messageManager;
    protected $session;
    protected $_checkoutSession;
    protected $helperData;//get config in Admin

    public function __construct(
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Checkout\Model\Session $_checkoutSession,
        \Mageplaza\GiftCard\Helper\Data $helperData

    )
    {
        $this->session = $session;
        $this->helperData = $helperData;
        $this->_checkoutSession = $_checkoutSession;
        $this->_giftCardFactory = $giftCardFactory;
        $this->_messageManager = $messageManager;
        parent::__construct($context);
    }


    public function aroundExecute(\Magento\Checkout\Controller\Cart\CouponPost $subject, callable $proceed)
    {

        if ($this->helperData->getGeneralConfig('enableGiftCard') == 0 ||
            $this->helperData->getGeneralConfig('enableUsedCheckOut') == 0) {
            return $proceed();
        }

        $code = $subject->getRequest()->getParam('coupon_code');
        $code = trim($code);
        $quote = $this->_checkoutSession->getQuote();


        //if have giftcard sesion run code , if have no, skip this code
        if ($subject->getRequest()->getParam('remove') == 1 && $this->getValue() == 'giftcard') {
            $this->messageManager->addSuccessMessage(
                __('You canceled GiftCard .')
            );
            $dataQuote = [
                'entity_id' => $this->_checkoutSession->getQuote()->getData('entity_id'),
                'giftcard_code' => '',
                'giftcard_base_discount' => '',
                'giftcard_discount' => ''
            ];
            $quote->setData($dataQuote)->save();

            $this->unSetValue();
            return $this->returnResult('*/*/index');
        }


        // get code giftcard and return code_gift
        $giftcard = $this->_giftCardFactory->create();
        $colection = $giftcard->getCollection();
        $colection->addFilter('code', $code);

        //insert data to  quote
        $data = $colection->getData();
        if ($data) {
            //   $logger->info(json_encode($data));
            $this->setValue('giftcard');
            $escaper = $this->_objectManager->get(\Magento\Framework\Escaper::class);
            $this->messageManager->addSuccessMessage(
                __(
                    'You used GiftCard code "%1".',
                    $escaper->escapeHtml($code)
                )
            );
            if ($data[0]['balance'] > $quote->getBaseGrandTotal()) {
                $discount = $quote->getBaseGrandTotal();
            } else {
                $discount = $data[0]['balance'];
            }
            $dataQuote = [
                'entity_id' => $this->_checkoutSession->getQuote()->getData('entity_id'),
                'giftcard_code' => $code,
                'giftcard_base_discount' => $discount,
                'giftcard_discount' => $discount
            ];
            $quote->setData($dataQuote)->save();

            return $this->returnResult('*/*/index', ['gift_code' => $code]);
        } else {
            return $proceed();
        }
    }

    function afterGetCouponCode(\Magento\Checkout\Block\Cart\Coupon $coupon, $result)
    {

        if ($this->helperData->getGeneralConfig('enableGiftCard') == 0 ||
            $this->helperData->getGeneralConfig('enableUsedCheckOut') == 0) {
            return $result;
        }

        $giftCode = $this->getRequest()->getParam('gift_code');
        if ($giftCode) {
            return $giftCode;

        } else {
            $giftCode = $this->_checkoutSession->getQuote()->getGiftcardCode();//check in quote if param null
            if ($giftCode) {
                return $giftCode;
            }
            return $result;
        }
    }

// this code below for set sesion giftcard value
    public function setValue($value)
    {
        $this->session->setAbc($value);

    }

    public function getValue()
    {
        // $this->session->start();
        return $this->session->getAbc();
    }

    public function unSetValue()
    {
        //  $this->session->start();
        return $this->session->unsAbc();
    }

//. end code for set sesion giftcard value
    private function returnResult($path = '', array $params = [])
    {
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($path, $params);
    }


    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
