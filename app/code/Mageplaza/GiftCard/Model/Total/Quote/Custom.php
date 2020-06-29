<?php


namespace Mageplaza\GiftCard\Model\Total\Quote;

class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;
    protected $helperData;//get config in Admin

    /**
     * Custom constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    )
    {
        $this->helperData = $helperData;
        $this->_priceCurrency = $priceCurrency;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|bool
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        $baseDiscount = $quote->getData('giftcard_base_discount');

        $discount = $this->_priceCurrency->convert($baseDiscount, ''
            , $quote->getData('quote_currency_code'));
        $total->addTotalAmount('customdiscount', -$discount);
        $total->addBaseTotalAmount('customdiscount', -$baseDiscount);
        $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
        $total->setBaseDiscountAmount($baseDiscount);
        $quote->setCustomDiscount(-$discount);
        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {

        if (!$this->isEnable()) return [];

        $discount = $this->_priceCurrency->convert(
            $quote->getGiftcardBaseDiscount(),
            $quote->getStore(),
            $quote->getQuoteCurrencyCode());
        return [
            'code' => 'custom_discount',
            'title' => $this->getLabel(),
            'value' => $discount
        ];
    }

    public function isEnable()
    {

        if ($this->helperData->getGeneralConfig('enableGiftCard') == 0 ||
            $this->helperData->getGeneralConfig('enableUsedCheckOut') == 0) {
            return false;
        } else return true;
    }

    public function getLabel()
    {
        return __('Custom Discount');
    }

}
