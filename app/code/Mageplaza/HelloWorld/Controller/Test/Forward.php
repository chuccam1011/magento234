<?php


namespace Mageplaza\HelloWorld\Controller\Test;


class Forward extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $this->_forward('aa');
    //    $this->_redirect('*/*/hello');

    }
}
