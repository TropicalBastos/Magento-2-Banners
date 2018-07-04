<?php
namespace GlobalGust\HeaderImages\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{

    /**
     * Add the new fields into the category fields array
     */
    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = 'header_image';
        $fields['content'][] = 'background_header_image';
        return $fields;
    }
    
}