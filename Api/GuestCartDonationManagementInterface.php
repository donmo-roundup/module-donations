<?php

namespace Donmo\Roundup\Api;

interface GuestCartDonationManagementInterface
{
    /**
     * Add Donmo donation to guest cart
     *
     * @param string $cartId (masked quote ID)
     * @param float $donationAmount
     * @return string
     */
    public function addDonationToCart(string $cartId, float $donationAmount): string;

    /**
     * Remove Donmo donation from guest cart
     *
     * @api
     * @param string $cartId (masked quote ID)
     * @return string
     */
    public function removeDonationFromCart(string $cartId) : string;
}
