<?php


namespace Mageplaza\GiftCard\Ui\Component\Listing\Column;


use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Zend_Currency_Exception;

class Price extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Column name
     */
   // const NAME = 'column.balance';

    /**
     * @var CurrencyInterface
     */
    protected $localeCurrency;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CurrencyInterface $localeCurrency
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CurrencyInterface $localeCurrency,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->localeCurrency = $localeCurrency;
        $this->storeManager = $storeManager;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws Zend_Currency_Exception|NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $store = $this->storeManager->getStore(
                $this->context->getFilterParam('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID)
            );
            $currency = $this->localeCurrency->getCurrency($store->getBaseCurrencyCode());

            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = $currency->toCurrency(sprintf("%f", $item[$fieldName]));
                }
            }
        }

        return $dataSource;
    }
}
