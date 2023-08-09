define([
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/quote',
    'mage/url',
    'Magento_Checkout/js/action/get-payment-information'
], function (Component, ko, totals, quote, url, getTotalsAction) {
    return Component.extend({
        defaults: {
            template: 'Donmo_Roundup/checkout/summary/donmo-block',
        },

        grandTotal: ko.observable(quote.totals()['grand_total']),

        addDonation:  ({ donationAmount }) => {
            const cartId = quote.getQuoteId();
            const path = url.build(`rest/V1/donmo/add_donation`)

            fetch(path, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    donationAmount,
                    cartId
                })
            }).then(response => {
                if(!response.ok) {
                    throw new Error();
                }
                return response.json()
            }).then(() => getTotalsAction())
        },

        removeDonation: () => {
            const cartId = quote.getQuoteId();
            const path = url.build(`rest/V1/donmo/remove_donation/${cartId}`)

            return fetch(path, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if(! response.ok) {
                    throw new Error();
                }
                return response.json()
            }).then(
                () => getTotalsAction())
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
