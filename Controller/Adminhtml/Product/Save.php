<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace GlobalGust\HeaderImages\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Save
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends \Magento\Catalog\Controller\Adminhtml\Product\Save
{

    const PRODUCT_HEADER_IMAGE = 'product_header_image';
    const PRODUCT_HEADER_BACKGROUND_IMAGE = 'product_header_back_image';

    /**
     * Save product header related images
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $resultRedirect = parent::execute();

        $pageHeaderImage = $this->getRequest()->getParam(self::PRODUCT_HEADER_IMAGE);
        $pageHeaderBackgroundImage = $this->getRequest()->getParam(self::PRODUCT_HEADER_BACKGROUND_IMAGE);

        /** Product may have been saved at this point */
        $productId = $this->getRequest()->getParam('id');
        $product = $this->productRepository->getById($productId);
        if($product){
            if(isset($this->getRequest()->getParam('product')[self::PRODUCT_HEADER_IMAGE])){
                if($headerImg = $this->getRequest()->getParam('product')[self::PRODUCT_HEADER_IMAGE]){
                    $product->setData(self::PRODUCT_HEADER_IMAGE, $headerImg[0]['name']);
                    $product->save();
                }
            }
            if(isset($this->getRequest()->getParam('product')[self::PRODUCT_HEADER_BACKGROUND_IMAGE])){
                if($headerBackgroundImg = $this->getRequest()->getParam('product')[self::PRODUCT_HEADER_BACKGROUND_IMAGE]){
                    $product->setData(self::PRODUCT_HEADER_BACKGROUND_IMAGE, $headerBackgroundImg[0]['name']);
                    $product->save();
                }
            }
        }

        return $resultRedirect;
    }

}
