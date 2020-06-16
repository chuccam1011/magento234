// /**
//  * Copyright © Magento, Inc. All rights reserved.
//  * See COPYING.txt for license details.
//  */
// /**
//  * @api
//  */
// define([
//     'ko',
//     'underscore',
//     'domReady!'
// ], function (ko, _) {
//     'use strict';
//
//     var proceedTotalsData = function (data) {
//             if (_.isObject(data) && _.isObject(data['extension_attributes'])) {
//                 _.each(data['extension_attributes'], function (element, index) {
//                     data[index] = element;
//                 });
//             }
//
//             return data;
//         },
//         totalsData = proceedTotalsData(window.checkoutConfig.totalsData),
//         totals = ko.observable(totalsData),
//         priceFormat = window.checkoutConfig.priceFormat,
//         disData = window.checkoutConfig.totalsData.total_segments,
//         collectedTotals = ko.observable({});
//
//
//     return {
//         discount: totals,
//
//         getDiscount: function () {
//             return '8';
//         },
//
//         getPriceFormat: function () {
//             return priceFormat;
//         },
//         setCollectedTotals: function (code, value) {
//             var colTotals = collectedTotals();
//             colTotals[code] = value;
//             collectedTotals(colTotals);
//         }
//
//     };
//
// });
