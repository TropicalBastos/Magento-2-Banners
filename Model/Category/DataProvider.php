<?php
namespace GlobalGust\HeaderImages\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{

    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = 'header_image'; // custom image field

        return $fields;
    }
}