define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Mageplaza_GiftCard/js/model/discount',
        'ko',
    ],
    function ($, Component, discount, ko) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Mageplaza_GiftCard/checkout/summary/customdiscount'
            },


            isDisplayedCustomdiscount: function () {
                let isDisplay = window.checkoutConfig.totalsData.total_segments[1].value;
                if (isDisplay > 0) {
                    return true;
                } else return false;

            },

            getCustomDiscount: function () {
                let dis_count = window.checkoutConfig.totalsData.total_segments[1].value;
                return this.getFormattedPrice(-dis_count);
            }
        });
    }
);
