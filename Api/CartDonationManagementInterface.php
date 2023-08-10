<?php

namespace Donmo\Roundup\Api;

interface CartDonationManagementInterface
{
    /**
     * Add Donmo donation to customer cart
     *
     * @param int $cartId
     * @param float $donationAmount
     * @return string
     */
    public function addDonationToCart(int $cartId, float $donationAmount): string;

    /**
     * Remove Donmo donation from customer cart
     *
     * @api
     * @param int $cartId
     * @return string
     */
    public function removeDonationFromCart(int $cartId) : string;
}
