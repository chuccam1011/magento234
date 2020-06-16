<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Widget\Controller\Adminhtml;

/**
 * @magentoAppArea adminhtml
 */
class WidgetTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    /**
     * Partially covers \Magento\Widget\Block\adminhtml\Widget\Options::_addField()
     */
    public function testLoadOptionsAction()
    {
        $this->getRequest()->setParam(
            'widget',
            '{"widget_type":"Magento\\\\Cms\\\\Block\\\\Widget\\\\Page\\\\Link","values":{}}'
        );
        $this->dispatch('backend/admin/widget/loadOptions');
        $output = $this->getResponse()->getBody();
        //searching for label with text "CMS Page"
        $this->assertContains(
            'data-ui-id="wysiwyg-widget-options-fieldset-element-label-parameters-page-id-label" >' . '<span>CMS Page',
            $output
        );
    }
}