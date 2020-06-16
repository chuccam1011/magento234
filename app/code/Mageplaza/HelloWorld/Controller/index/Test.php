<?php
namespace Mageplaza\HelloWorld\Controller\Index;
use Magento\Framework\App\ResponseInterface;

class Test extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $textDisplay = new \Magento\Framework\DataObject(array('text' => 'Mageplaza_Hellowwrd'));
         $this->_eventManager->dispatch('mageplaza_helloworld_display_text', ['mp_text' => $textDisplay]);
     //   $this->_eventManager->dispatch('sales_quote_remove_item', ['quote_item' => $item]);
        echo $textDisplay->getText();
    }
}
