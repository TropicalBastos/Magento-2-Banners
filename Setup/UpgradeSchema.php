<?php

namespace GlobalGust\HeaderImages\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Upgrade Data script
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $setup->startSetup();
            $connection = $setup->getConnection();
            $connection->addColumn($setup->getTable('cms_page'), 'page_header_image',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Page Header Image'
                ]);

            $setup->endSetup();
        }

        /**
         * v1.0.4 adds in the background header images
         */
        if(version_compare($context->getVersion(), '1.0.4', '<')){
            $setup->startSetup();
            $connection = $setup->getConnection();
            $connection->addColumn($setup->getTable('cms_page'), 'page_header_background_image',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Page Header Background Image'
            ]);

            $setup->endSetup();
        }
    }

}
