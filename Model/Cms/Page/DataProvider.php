<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace GlobalGust\HeaderImages\Model\Cms\Page;

use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Cms\Model\Page;
use GlobalGust\HeaderImages\Helper\File as FileManager;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Cms\Model\Page\DataProvider
{

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        /** @var $page \Magento\Cms\Model\Page */
        foreach ($items as $page) {
            $this->loadedData[$page->getId()] = $page->getData();
        }

        $data = $this->dataPersistor->get('cms_page');

        if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();
            $this->dataPersistor->clear('cms_page');
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        $mediaUrl=$currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        if($this->loadedData){
            if(!empty($this->loadedData[$page->getId()]['page_header_image'])){
                $this->processFile('page_header_image', $page, $mediaUrl);
            }
    
            if(!empty($this->loadedData[$page->getId()]['page_header_background_image'])){
                $this->processFile('page_header_background_image', $page, $mediaUrl);
            }
        }

        return $this->loadedData;
    }

    /** Processes the file data for interpretation by the view
     * @return void
     */
    protected function processFile(string $fileName, Page $page, string $mediaUrl)
    {
        $imageName = $this->loadedData[$page->getId()][$fileName];
        unset($this->loadedData[$page->getId()][$fileName]);
        $imageUrl = $mediaUrl."cms/headerimages/".$imageName;
        $imageBytes = FileManager::getFileSize($imageUrl);
        $this->loadedData[$page->getId()][$fileName][0]['name'] = $imageName;
        $this->loadedData[$page->getId()][$fileName][0]['url'] = $imageUrl;
        $this->loadedData[$page->getId()][$fileName][0]['size'] = $imageBytes;
        $this->loadedData[$page->getId()][$fileName][0]['type'] = 'image';
    }

}