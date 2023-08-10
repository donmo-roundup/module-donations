define([
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/url-builder',
    'Magento_Checkout/js/action/get-payment-information',
    'mage/storage'
], function (Component, ko, totals, quote, customer, urlBuilder, getPaymentInformation, storage) {
    return Component.extend({
        defaults: {
            template: 'Donmo_Roundup/checkout/summary/donmo-block',
        },

        grandTotal: ko.observable(quote.totals()['grand_total']),

        addDonation:  ({ donationAmount }) => {
            const cartId = quote.getQuoteId();
            let serviceUrl
            if (!customer.isLoggedIn()) {
                serviceUrl = urlBuilder.createUrl('/guest-carts/:cartId/add_donmo_donation', { cartId });
            } else {
                serviceUrl = urlBuilder.createUrl('/carts/mine/add_donmo_donation', {});
            }

            const payload = {
                cartId,
                donationAmount
            };

            return storage.post(
                serviceUrl,
                JSON.stringify(payload)
            ).then(() => getPaymentInformation())
        },

        removeDonation: () => {
            let serviceUrl
            if (!customer.isLoggedIn()) {
                serviceUrl = urlBuilder.createUrl('/guest-carts/:cartId/remove_donmo_donation', {
                    cartId: quote.getQuoteId()
                });
            } else {
                serviceUrl = urlBuilder.createUrl('/carts/mine/remove_donmo_donation', {});
            }

            return storage.delete(serviceUrl).then(() => getPaymentInformation())
        },

        insertIntegration: function (){
            const donmo = DonmoRoundup(
                {
                    publicKey: this.donmoConfig.publicKey,
                    isBackendBased: true,
                    language: this.donmoConfig.language,
                    orderId: quote.getQuoteId(),
                    integrationTitle: this.donmoConfig.integrationTitle,
                    roundupMessage: this.donmoConfig.roundupMessage,
                    thankMessage: this.donmoConfig.thankMessage,
                    errorMessage: this.donmoConfig.errorMessage,
                    addDonationAction: this.addDonation,
                    removeDonationAction: this.removeDonation,
                    getExistingDonation: () => parseFloat(totals.getSegment('donmodonation')?.value),
                    getGrandTotal: () => quote.totals()['grand_total'],
                }
            )

            donmo.build()

            // on totals change, trigger grandTotal observable with its value
            quote.totals.subscribe((data) => this.grandTotal(data['grand_total']))

            // on grandTotal change, refresh donmo integration
            this.grandTotal.subscribe(() => donmo.refresh())
        }

    })

})
