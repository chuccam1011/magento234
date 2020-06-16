<?php
namespace Mageplaza\HelloWorld\Controller\index;
use Magento\Framework\App\ResponseInterface;

class Display  extends \Magento\Framework\App\Action\Action
{

    protected $_pageFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        return $this->_pageFactory->create();
    }
}
