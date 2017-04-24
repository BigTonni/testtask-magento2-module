<?php

namespace Test\TestTask\Ui\Component\Listing\Columns;

class ProductStock extends \Magento\Ui\Component\Listing\Columns\Column {

     /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
     
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ){
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $StockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
            $StockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');
           
            foreach ($dataSource['data']['items'] as $key => $item) {
                $productStockObj = $StockRegistry->getStockItem($item['entity_id']);
                $stock = $StockState->getStockQty($productStockObj['product_id'], $productStockObj['website_id']);
                $dataSource['data']['items'][$key]['product_stock'] = (($stock > 0) ? 'In stock' : 'Out of stock');
            }
        }

        return $dataSource;
    }
}
