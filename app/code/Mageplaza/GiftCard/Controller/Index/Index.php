<?php


namespace Mageplaza\GiftCard\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_giftCardFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory

    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_giftCardFactory = $giftCardFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $this->addGiftCard();
        $this->deleteGiftCard();
        $this->editGiftCard();
        return $this->_pageFactory->create();

    }

    public function getList()
    {

    }

    public function addGiftCard()
    {
        $giftCard = $this->_giftCardFactory->create();
        $giftCardRequest = $this->getRequest()->getParams();
        // var_dump( $this->getRequest()->getParams());
        if (isset($giftCardRequest['submit_add']) && $giftCardRequest['code'] != null) {
            $data = [
                'code' => $giftCardRequest['code'],
                'balance' => $giftCardRequest['balance'],
            ];
            $giftCard->addData($data)->save();
            echo 'insert susses';
        }

    }

    public function editGiftCard()
    {
        $giftCard = $this->_giftCardFactory->create();

        $giftCardRequest = $this->getRequest()->getParams();
        print_r($giftCardRequest);
        $editIDRequest = $this->getRequest()->getParam('edit');
        if (isset($editIDRequest) && $editIDRequest != null) {
            if (isset($giftCardRequest['balance']) && $giftCardRequest['balance'] != null) {
                $data = [
                    'giftcard_id' => $editIDRequest,
                   // 'code' => $giftCardRequest['code'],
                    'balance' => $giftCardRequest['balance']
                ];
                $giftCard->load($editIDRequest);
                if ($giftCard->getData('giftcard_id')) {
                    $giftCard->setData($data)->save();
                    echo 'edit sucsses';
                }
            }
        }
    }

    public function deleteGiftCard()
    {
        // var_dump($this->getRequest()->getParam('del'));
        $delIDRequest = $this->getRequest()->getParam('del');
        $giftCard = $this->_giftCardFactory->create();
        if (isset($delIDRequest) && $delIDRequest != null) {
            $giftCard->load($delIDRequest);
            $giftCard->delete();
        }
    }


}
