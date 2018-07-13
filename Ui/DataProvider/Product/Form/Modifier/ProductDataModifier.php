<?php

namespace GlobalGust\HeaderImages\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use GlobalGust\HeaderImages\Helper\File as FileManager;

class ProductDataModifier extends AbstractModifier
{

    /** Literal path to media storage  */
    const PRODUCT_HEADER_MEDIA_URL = 'catalog/product/headerimages/';

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $mainData = array_values($data)[0]['product'];
        $productId = array_keys($data)[0];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
        $header = $product->getData('product_header_image');
        $background = $product->getData('product_header_back_image');

        if($header){
            $this->processFile($mainData, 'product_header_image', $header); 
        }

        if($background){
            $this->processFile($mainData, 'product_header_back_image', $background); 
        }

        $data[$productId] = [
            'product' => $mainData
        ];

        return $data;
    }

    /** Processes the file data for view
     * @return void
     */
    protected function processFile(array &$data, $key, $imageName)
    {
        $mediaUrl = $this->getMediaUrlLocal();
        $imageUrl = $mediaUrl. self::PRODUCT_HEADER_MEDIA_URL . $imageName;
        $imageBytes = FileManager::getFileSize($imageUrl);
        $data[$key][0]['name'] = $imageName;
        $data[$key][0]['url'] = $imageUrl;
        $data[$key][0]['size'] = $imageBytes;
        $data[$key][0]['type'] = 'image';
    }

    /**
     * @return string
     */
    protected function getMediaUrlLocal()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }

}