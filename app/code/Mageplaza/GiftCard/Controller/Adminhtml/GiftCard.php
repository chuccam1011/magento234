<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Dashboard admin controller
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */

namespace Mageplaza\GiftCard\Controller\Adminhtml;

abstract class GiftCard extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Mageplaza_GiftCard::giftcard';
}
