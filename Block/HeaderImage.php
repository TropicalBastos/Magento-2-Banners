<?php
/**
 * Copyright © 2018 Ian Bastos.com. All rights reserved.

 * @author Ian Bastos Team <contact@Ian Bastos.com>
 */

namespace GlobalGust\HeaderImages\Block;

class HeaderImage extends \Magento\Framework\View\Element\Template {

    const CATEGORY_PAGE = 'catalog_category_view';
    const CMS_PAGE = 'cms_page_view';
    const PRODUCT_PAGE = 'catalog_product_view';
    const CURRENT_CATEGORY = 'current_category';
    const HEADER_IMAGE = 'header_image';
    const PAGE_HEADER_IMAGE = 'page_header_image';
    const NO_ROUTE_PAGE = 'cms_noroute_index';
    const CATEGORY_MEDIA_PREFIX = '/pub/media/catalog/category/';
    const CMS_MEDIA_PREFIX = '/pub/media/cms/headerimages/';
    const PRODUCT_MEDIA_PREFIX = '/pub/media/catalog/product/headerimages/';
    const CATEGORY_BACKGROUND_HEADER_IMAGE = 'background_header_image';
    const PAGE_BACKGROUND_HEADER_IMAGE = 'page_header_background_image';
    const PRODUCT_HEADER_IMAGE = 'product_header_image';
    const PRODUCT_HEADER_BACKGROUND_IMAGE = 'product_header_back_image';

    protected $_template = 'GlobalGust_HeaderImages::header_image.phtml';
    protected $_request;
    protected $_pageType;
    protected $_registry;
    protected $_page;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        \Magento\Cms\Model\Page $page,
        array $data = []
    ) {
        $this->_request = $request;
        $this->_registry = $registry;
        $this->_page = $page;
        parent::__construct($context, $data);
    }

    /** Check if the current context is a category page
     * if so set the pagetype protected member to the category page
     * @return bool
     */
    public function isCategoryPage()
    {
        if ($this->getFullActionName() == self::CATEGORY_PAGE) {
            $this->_pageType = self::CATEGORY_PAGE;
            return true;
        }

        return false;
    }

    /** Check if the current context is a cms page
     * @return bool
     */
    public function isCmsPage()
    {
        if ($this->getFullActionName() == self::CMS_PAGE 
        || $this->getFullActionName() == self::NO_ROUTE_PAGE) {
            $this->_pageType = self::CMS_PAGE;
            return true;
        }

        return false;
    }

    /** Checks whether page is product page
     * @return bool
     */
    public function isProductPage()
    {
        if ($this->getFullActionName() == self::PRODUCT_PAGE) {
            $this->_pageType = self::PRODUCT_PAGE;
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getFullActionName()
    {
        return $this->_request->getFullActionName();
    }

    /**
     * @return \Magento\Catalog\Model\Category
     */
    public function getCategory()
    {
        return $this->_registry->registry('current_category');
    }

    /**
     * @return \Magento\Cms\Model\Page
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->_registry->registry('product');
    }

    /** Get the url of the header image
     * @return null|string
     */
    public function getHeaderImageUrl()
    {
        $headerImage = null;

        switch($this->_pageType){
            case self::CATEGORY_PAGE:
                $headerImage = $this->getBaseUrl() . self::CATEGORY_MEDIA_PREFIX
                    . $this->getCategory()->getData(self::HEADER_IMAGE);
                break;

            case self::CMS_PAGE:
                $headerImage = $this->getBaseUrl() . self::CMS_MEDIA_PREFIX .
                    $this->getPage()->getData(self::PAGE_HEADER_IMAGE);
                break;

            case self::PRODUCT_PAGE:
                $headerImage = $this->getBaseUrl() . self::PRODUCT_MEDIA_PREFIX .
                    $this->getProduct()->getData(self::PRODUCT_HEADER_IMAGE);
                break;

            default:
                break;
        }

        return $headerImage;
    }

    /** Get the url of the background header image
     * @return null|string
     */
    public function getBackgroundHeaderImageUrl()
    {
        $background = null;

        switch($this->_pageType){
            case self::CATEGORY_PAGE:
                $background = $this->getBaseUrl() . self::CATEGORY_MEDIA_PREFIX
                    . $this->getCategory()->getData(self::CATEGORY_BACKGROUND_HEADER_IMAGE);
                break;

            case self::CMS_PAGE:
                $background = $this->getBaseUrl() . self::CMS_MEDIA_PREFIX .
                    $this->getPage()->getData(self::PAGE_BACKGROUND_HEADER_IMAGE);
                break;

            case self::PRODUCT_PAGE:
                $background = $this->getBaseUrl() . self::PRODUCT_MEDIA_PREFIX .
                    $this->getProduct()->getData(self::PRODUCT_HEADER_BACKGROUND_IMAGE);
                break;

            default:
                break;
        }

        return $background;
    }

    /** Check if block has header image
     * @return bool
     */
    public function hasHeaderImage()
    {
        $hasHeaderImage = false;

        if($this->isCategoryPage()){
            if($this->getCategory()->getData(self::HEADER_IMAGE))
                $hasHeaderImage = true;
        }

        if($this->isCmsPage()){
            if($this->getPage()->getData(self::PAGE_HEADER_IMAGE))
                $hasHeaderImage = true;
        }

        if($this->isProductPage()){
            if($this->getProduct()->getData(self::PRODUCT_HEADER_IMAGE))
                $hasHeaderImage = true;
        }

        return $hasHeaderImage;
    }

    /** Check if block has background header image
     * @return bool
     */
    public function hasBackgroundHeaderImage()
    {
        $hasBackground = false;

        if($this->isCategoryPage()){
            if($this->getCategory()->getData(self::CATEGORY_BACKGROUND_HEADER_IMAGE))
                $hasBackground = true;
        }

        if($this->isCmsPage()){
            if($this->getPage()->getData(self::PAGE_BACKGROUND_HEADER_IMAGE))
                $hasBackground = true;
        }

        if($this->isProductPage()){
            if($this->getProduct()->getData(self::PRODUCT_HEADER_BACKGROUND_IMAGE))
                $hasBackground = true;
        }

        return $hasBackground;
    }

}
