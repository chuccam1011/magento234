<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Magento\Backend\App\Action;

class Create extends Action

{

    protected $resultPageFactory;

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
        $this->_forward('edit');
    }

}
