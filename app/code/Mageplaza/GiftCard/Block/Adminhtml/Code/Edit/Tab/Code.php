<?php

namespace Mageplaza\GiftCard\Block\Adminhtml\Code\Edit\Tab;

class Code extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    protected $_giftCardFactory;
    protected $helperData;

    public function __construct(
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Mageplaza\GiftCard\Helper\DataCodeLength $helperData,
        array $data = []
    )
    {
        $this->_giftCardFactory = $giftCardFactory;
        $this->helperData = $helperData;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $id = $this->getRequest()->getParam('giftcard_id');
        $form = $this->_formFactory->create();
        $giftcard = $this->_giftCardFactory->create();
        $data = $giftcard->load($id);
        //  print_r($data);
        if (isset($data['giftcard_id']) && $data['giftcard_id'] != null) {
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Gift Card Infomation')]);
            $fieldset->addField('code', 'text', [
                'name' => 'code',
                'label' => __('Code'),
                'title' => __('Template Name'),
                'value' => $data['code'],
                'readonly' => true
            ]);
            $fieldset->addField('created_from', 'text', [
                'name' => 'created_from',
                'label' => __('Create From'),
                'title' => __('Template Name'),
                'value' => $data['created_from'],
                'readonly' => true

            ]);
            $fieldset->addField('balance', 'text', [
                'name' => 'balance',
                'label' => __('Balance'),
                'title' => __('Template Name'),
                'value' => $data['balance'],
                'class' => 'validate-greater-than-zero',
                'required' => true
            ]);
            $fieldset->addField('giftcard_id', 'hidden', [
                'name' => 'giftcard_id',
                'value' => $data['giftcard_id']
            ]);
            $this->setForm($form);
            return parent::_prepareForm();

        } else {
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Gift Card Infomation')]);
            $fieldset->addField('code_length', 'text', [
                'name' => 'length',
                'value' => $this->helperData->getGeneralConfig('codelength'),
                'label' => __('Code Length'),
                'title' => __('Template Name'),
                'class' => 'validate-greater-than-zero'
            ]);
            $fieldset->addField('balance', 'text', [
                'name' => 'balance',
                'label' => __('Balance'),
                'title' => __('Template Name'),
                'class' => 'validate-greater-than-zero',
                'required' => true,
            ]);

            $this->setForm($form);
            return parent::_prepareForm();
        }

    }


    public function getTabLabel()
    {
        return __('Gift card information');
    }

    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
