<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Mageplaza\GiftCard\Controller\Adminhtml\GiftCard;

class Index extends GiftCard

{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Codes')));
        return $resultPage;
    }

}
