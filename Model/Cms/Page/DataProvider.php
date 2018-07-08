<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace GlobalGust\HeaderImages\Model\Cms\Page;

use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Cms\Model\Page;

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

        if(!empty($this->loadedData[$page->getId()]['page_header_image'])){
            $this->processFile('page_header_image', $page, $mediaUrl);
        }

        if(!empty($this->loadedData[$page->getId()]['page_header_background_image'])){
            $this->processFile('page_header_background_image', $page, $mediaUrl);
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
        $imageBytes = $this->getFileSize($imageUrl);
        $this->loadedData[$page->getId()][$fileName][0]['name'] = $imageName;
        $this->loadedData[$page->getId()][$fileName][0]['url'] = $imageUrl;
        $this->loadedData[$page->getId()][$fileName][0]['size'] = $imageBytes;
        $this->loadedData[$page->getId()][$fileName][0]['type'] = 'image';
    }

    /**
     * Returns the size of a file without downloading it, or -1 if the file
     * size could not be determined.
     *
     * @param $url - The location of the remote file to download. Cannot
     * be null or empty.
     *
     * @return The size of the file referenced by $url, or -1 if the size
     * could not be determined.
     */
    public function getFileSize( $url ) 
    {
        // Assume failure.
        $result = -1;
    
        $curl = curl_init( $url );
    
        // Issue a HEAD request and follow any redirects.
        curl_setopt( $curl, CURLOPT_NOBODY, true );
        curl_setopt( $curl, CURLOPT_HEADER, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
    
        $data = curl_exec( $curl );
        curl_close( $curl );
    
        if( $data ) {
        $content_length = "unknown";
        $status = "unknown";
    
        if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
            $status = (int)$matches[1];
        }
    
        if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
            $content_length = (int)$matches[1];
        }
    
        // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        if( $status == 200 || ($status > 300 && $status <= 308) ) {
            $result = $content_length;
        }
        }
    
        return $result;
    }
}