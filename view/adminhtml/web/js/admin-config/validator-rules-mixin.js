define([
    'jquery'
], function ($) {
    'use strict';
    return function (target) {
        $.validator.addMethod(
            'validate-pk-live-key',
            function (value) {
                return value.startsWith("pk_live_")
            },
            $.mage.__('The key should start with pk_live_')
        );

        $.validator.addMethod(
            'validate-pk-test-key',
            function (value) {
                return value.startsWith("pk_test_")
            },
            $.mage.__('The key should start with pk_test_')
        );

        $.validator.addMethod(
            'validate-sk-live-key',
            function (value) {
                return value.startsWith("sk_live_")
            },
            $.mage.__('The key should start with sk_live_')
        );

        $.validator.addMethod(
            'validate-sk-test-key',
            function (value) {
                return value.startsWith("sk_test_")
            },
            $.mage.__('The key should start with sk_test_')
        );

        return target;
    };
});


