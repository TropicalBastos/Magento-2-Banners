<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace GlobalGust\HeaderImages\Model\Cms\Page;

use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

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
        $page = $this->collection->getNewEmptyItem();

        if (!empty($data)) {
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();
            $this->dataPersistor->clear('cms_page');
        }

        if(!empty($this->loadedData[$page->getId()]['page_header_image'])){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
            $currentStore = $storeManager->getStore();
            $media_url=$currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

            $image_name=$this->loadedData[$page->getId()]['page_header_image'];
            unset($this->loadedData[$page->getId()]['page_header_image']);
            $this->loadedData[$page->getId()]['page_header_image'][0]['name']=$image_name;
            $this->loadedData[$page->getId()]['page_header_image'][0]['url']=$media_url."cms/headerimages/".$image_name;
        }
        return $this->loadedData;
    }
}