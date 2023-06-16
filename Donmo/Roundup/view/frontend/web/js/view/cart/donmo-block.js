define([
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/quote',
    'mage/url',
    'Magento_Checkout/js/action/get-totals',
], function (Component, ko, totals, quote, url, getTotalsAction) {
    return Component.extend({
        defaults: {
            template: 'Donmo_Roundup/checkout/summary/donmo-block',
        },

        donmo: new DonmoRoundup(),

        grandTotal: ko.observable(quote.totals()['grand_total']),

        addDonation: ({ donationAmount }) => {
            const path = url.build('donmo/roundup/create');

            fetch(path, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    amount: donationAmount
                })
            }).then(response => response.json()).then(() => getTotalsAction([]))
        },

        removeDonation: () => {
            const path = url.build('donmo/roundup/remove');

            return fetch(path, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json()).then(
                () => getTotalsAction([])).then(() => console.log('remove donation completed'))
        },


        buildIntegration: function () {
            this.donmo.build({
                publicKey: this.donmoConfig.publicKey,
                isBackendBased: true,
                language: this.donmoConfig.language,
                width: '100%',
                orderId: quote.getQuoteId(),
                integrationTitle: this.donmoConfig.integrationTitle,
                roundupMessage: this.donmoConfig.roundupMessage,
                thankMessage: this.donmoConfig.thankMessage,
                errorMessage: this.donmoConfig.errorMessage,
                addDonationAction: this.addDonation,
                removeDonationAction: this.removeDonation,
                getExistingDonation: () => parseFloat(totals.getSegment('donmodonation')?.value),
                getGrandTotal: () => quote.totals()['grand_total'],
            })
        },

        insertIntegration: function () {
            console.log('inserting integration')
            this.buildIntegration()

            // on totals change, trigger grandTotal observable with new value
            quote.totals.subscribe((data) => this.grandTotal(data['grand_total']))

            // on grandTotal change, refresh donmo integration
            this.grandTotal.subscribe(() => this.donmo.refresh())
        }

    })

})
