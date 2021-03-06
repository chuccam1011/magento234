<?php


namespace Mageplaza\GiftCard\Ui\Component\Listing\Column;


use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(

        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []

    )
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws \Zend_Currency_Exception
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');

            foreach ($dataSource['data']['items'] as &$item) {
                $str = $item['balance'];
                $str = str_replace("$", "", $str);
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'mageplaza_giftcard/code/edit',
                        [
                            'giftcard_id' => $item['giftcard_id'],
                        ]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                    '__disableTmpl' => true
                ];

            }

        }
        return $dataSource;
    }


}
