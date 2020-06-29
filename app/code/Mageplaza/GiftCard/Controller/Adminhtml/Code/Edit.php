<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Mageplaza\GiftCard\Controller\Adminhtml\GiftCard;

class Edit extends GiftCard implements HttpGetActionInterface

{
    protected $resultPageFactory = false;
    protected $_giftCardFactory;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory

    )
    {
        parent::__construct($context);
        $this->_giftCardFactory = $giftCardFactory->create();
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $title = 'Create Gift Card';
        $id = $this->getRequest()->getParam('giftcard_id');
        if ($id) {
            $this->_giftCardFactory->load((int)$id);
            $title = 'Gift Card : ' . $this->_giftCardFactory->getCode();
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__($title)));
        return $resultPage;
    }

}
