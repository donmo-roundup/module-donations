define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals',
], function (Component, quote, priceUtils, totals) {
    return Component.extend({
        defaults: {
            template: 'Donmo_Roundup/cart/totals/donmodonation'
        },
        getDonationLabel: function () {
            return this.donmoConfig.donationLabel
        },
        getDonationAmount: function () {
            let donationAmount = 0
            if(quote.getTotals()){
                donationAmount = parseFloat(totals.getSegment('donmodonation')?.value)
            }
            if (donationAmount && donationAmount > 0){
                 return this.getFormattedPrice(donationAmount)
            } else {
                return donationAmount;
            }
        }
    })
})
