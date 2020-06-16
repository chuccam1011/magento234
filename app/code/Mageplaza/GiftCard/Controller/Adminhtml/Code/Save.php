<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\LayoutFactory;

class Save extends Action
{
    protected $_giftCardFactory;
    protected $_giftCardRequest;
    protected $_messageManager;
    protected $layoutFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        LayoutFactory $layoutFactory


    )
    {
        $this->_giftCardFactory = $giftCardFactory->create();
        $this->_messageManager = $messageManager;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_giftCardRequest = $this->getRequest()->getParams();
        //$idGiftCard = $this->_giftCardFactory->getId();can not get Id
   //     print_r($this->_giftCardRequest);
        //blac is deferent betwen save and sava pim ary
        if (isset($this->_giftCardRequest['back']) && $this->_giftCardRequest['balance'] != null) {
            if (isset($this->_giftCardRequest['code'])) {
                $data = [
                    'giftcard_id' => $this->_giftCardRequest['giftcard_id'],
                    'balance' => $this->_giftCardRequest['balance']
                ];
                $this->_giftCardFactory->load($this->_giftCardRequest['giftcard_id']);
                if ($this->_giftCardFactory->getData('giftcard_id')) {
                    $this->_giftCardFactory->setData($data)->save();
                    $this->messageManager->addSuccess(__("GiftCard Gift Card Susses"));
                    return $this->returnResult('*/*/edit', [
                        'code' => $this->_giftCardFactory->getData('code'),
                        'balance' => $this->_giftCardFactory->getData('balance'),
                        'created_from' => $this->_giftCardFactory->getData('created_from'),

                    ], ['error' => false]);

                }

            } else {

                $code = $this->getCodeRandom($this->_giftCardRequest['length']);
                $data = [
                    'code' => $code,
                    'balance' => $this->_giftCardRequest['balance'],
                    'created_from' => 'admin'
                ];
                $this->_giftCardFactory->addData($data)->save();
                $giftcard_id = $this->_giftCardFactory->getId();
                $this->messageManager->addSuccess(__("Contine GiftCard Gift Card"));
                return $this->returnResult('*/*/edit', [
                    'code' => $code,
                    'balance' => $this->_giftCardRequest['balance'],
                    'created_from' => 'admin',
                    'giftcard_id' => $giftcard_id
                ], ['error' => false]);

            }


        } else {
            if (isset($this->_giftCardRequest['code'])) {
                $data = [
                    'giftcard_id' => $this->_giftCardRequest['giftcard_id'],
                    'balance' => $this->_giftCardRequest['balance']
                ];
                $this->_giftCardFactory->load($this->_giftCardRequest['giftcard_id']);
                if ($this->_giftCardFactory->getData('giftcard_id')) {
                    $this->_giftCardFactory->setData($data)->save();
                    //echo 'a';
                    $this->messageManager->addSuccess(__("GiftCard Gift Card Susses"));
                    return $this->returnResult('*/*/index', [], ['error' => false]);

                }

            } else {

                if (isset($this->_giftCardRequest['balance']) && $this->_giftCardRequest['balance'] != null) {
                    $code = $this->getCodeRandom($this->_giftCardRequest['length']);
                    $data = [
                        'code' => $code,
                        'balance' => $this->_giftCardRequest['balance'],
                        'created_from' => 'admin'
                    ];
                    $this->_giftCardFactory->addData($data)->save();
                    $this->messageManager->addSuccess(__("Create Gift Card Success"));
                    // $giftcard_id = $this->_giftCardFactory->getId();

                    return $this->returnResult('*/*/index', [], ['error' => false]);
                }

            }
        }

    }


    private function getCodeRandom($n)
    {
        $characters = 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    private function returnResult($path = '', array $params = [], array $response = [])
    {
//        if($this->isAjax()){
//            $layout = $this->layoutFactory->create();
//            $layout->initMessages();
//            $response['messages'] = [$layout->getMessagesBlock()->getGroupedHtml()];
//            $response['params'] = $params;
//            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($response);
//        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($path, $params);
    }

}

