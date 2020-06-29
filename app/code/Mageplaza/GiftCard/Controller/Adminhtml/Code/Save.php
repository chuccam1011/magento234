<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Save extends Action
{
    protected $_giftCardFactory;
    protected $_giftCardRequest;
    protected $_messageManager;
    protected $helperData;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Mageplaza\GiftCard\Helper\DataCodeLength $helperData
    )
    {
        $this->_giftCardFactory = $giftCardFactory->create();
        $this->_messageManager = $messageManager;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_giftCardRequest = $this->getRequest()->getParams();

        //back is deferent betwen save and save pimary

        if (isset($this->_giftCardRequest['back']) && $this->_giftCardRequest['balance'] != null) {
            // save and Continue edit not go index
            if (isset($this->_giftCardRequest['code'])) {
                $data = [
                    'giftcard_id' => $this->_giftCardRequest['giftcard_id'],
                    'balance' => $this->_giftCardRequest['balance']
                ];
                $this->_giftCardFactory->load($this->_giftCardRequest['giftcard_id']);
                if ($this->_giftCardFactory->getData('giftcard_id')) {
                    $this->_giftCardFactory->setData($data)->save();
                    $this->messageManager->addSuccess(__("Edit GiftCard Gift Card Susses"));
                    return $this->returnResult('*/*/edit', [
                        'giftcard_id' => $this->_giftCardRequest['giftcard_id']
                    ]);

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
                $this->messageManager->addSuccess(__("Continue Edit Gift Card"));
                return $this->returnResult('*/*/edit', [
                    'giftcard_id' => $giftcard_id
                ]);

            }

        } else { // save primary and go index

            if (isset($this->_giftCardRequest['code'])) {
                $data = [
                    'giftcard_id' => $this->_giftCardRequest['giftcard_id'],
                    'balance' => $this->_giftCardRequest['balance']
                ];
                $this->_giftCardFactory->load($this->_giftCardRequest['giftcard_id']);
                if ($this->_giftCardFactory->getData('giftcard_id')) {
                    $this->_giftCardFactory->setData($data)->save();
                    //echo 'a';
                    $this->messageManager->addSuccess(__("Edit GiftCard Gift Card Susses"));
                    return $this->returnResult('*/*/index', []);

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

                    return $this->returnResult('*/*/index', []);
                }

            }
        }

    }


    private function getCodeRandom($n)
    {
        if (!is_numeric($n)) {
            $n = $this->helperData->getGeneralConfig('codelength');
        }
        $characters = 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    private function returnResult($path = '', array $params = [])
    {
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($path, $params);
    }
}

