<?php

namespace GlobalGust\HeaderImages\Controller\Adminhtml\Cms\Image;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action
{
    /**
    * Image uploader
    *
    * @var \[Namespace]\[Module]\Model\ImageUploader
    */
    protected $imageUploader;

    /**
    * @param \Magento\Backend\App\Action\Context $context
    * @param \Magento\Catalog\Model\ImageUploader $imageUploader
    */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ImageUploader $imageUploader
    ) {
    parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }


    /**
    * Upload file controller action
    *
    * @return \Magento\Framework\Controller\ResultInterface
    */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('page_header_image');

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
?>