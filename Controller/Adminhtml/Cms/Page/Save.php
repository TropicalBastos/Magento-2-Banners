<?php

namespace GlobalGust\HeaderImages\Controller\Adminhtml\Cms\Page;

use Magento\Backend\App\Action;
use Magento\Cms\Model\Page;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Cms\Controller\Adminhtml\Page\Save
{
    /**
     * Attribute codes for new fields
     */
    const PAGE_HEADER_BACKGROUND_IMAGE = 'page_header_background_image';
    const PAGE_HEADER_IMAGE = 'page_header_image';

    /**
     * Execute save - modifies page header image values if they
     * exist so as to comply with varchar for db storage
     * 
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $pageHeader = $this->getRequest()->getParam(self::PAGE_HEADER_IMAGE);
        $pageBackground = $this->getRequest()->getParam(self::PAGE_HEADER_BACKGROUND_IMAGE);

        // Adapt request params to be compatible with cms page save
        if(isset($pageHeader) && is_array($pageHeader)){
            $this->getRequest()->setPostValue(self::PAGE_HEADER_IMAGE, $pageHeader[0]['name']);
        }

        if(isset($pageBackground) && is_array($pageBackground)){
            $this->getRequest()->setPostValue(self::PAGE_HEADER_BACKGROUND_IMAGE, $pageBackground[0]['name']);
        }

        return parent::execute();
    }

}
