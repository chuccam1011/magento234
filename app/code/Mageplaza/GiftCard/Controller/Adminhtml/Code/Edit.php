<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Mageplaza\GiftCard\Controller\Adminhtml\GiftCard;

class Edit extends GiftCard implements HttpGetActionInterface

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
        $data = $this->getRequest()->getParams();
        $tiltle = 'Create Gift Card';
        if (isset($data['code'])) {
            // print_r($data);
            $tiltle = 'Gift Card : '.$data['code'];
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__($tiltle)));
        return $resultPage;
    }

}
