<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Magento\Backend\App\Action;

class Delete extends Action
{
    protected $_giftCardFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory

    )
    {
        $this->_giftCardFactory = $giftCardFactory->create();
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('giftcard_id');
        echo $id;
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->_giftCardFactory->load($id);
        try {
            $this->_giftCardFactory->delete();
            $this->messageManager->addSuccessMessage(__('Succes Delete Gift Card '));
            return $resultRedirect->setPath('*/*/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath(
                '*/*/edit',
                ['attribute_id' => $id]
                );
        }

    }
}
