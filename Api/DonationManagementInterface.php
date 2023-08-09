<?php

namespace Donmo\Roundup\Api;

use Donmo\Roundup\Api\Data\DonationInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order;

interface DonationManagementInterface
{
    const STATUS_PENDING = "PENDING";
    const STATUS_CONFIRMED = "CONFIRMED";
    const STATUS_CANCELED = "CANCELED";
    const STATUS_REFUNDED = "REFUNDED";
    const STATUS_SENT = "SENT";

    /**
     * @param  Order $order
     * @throws CouldNotSaveException
     * @return void
     */
    public function createDonation(Order $order): void;

    /**
     * @param Order $order
     * @throws NoSuchEntityException
     * @return DonationInterface
     */
    public function getByOrder(Order $order): DonationInterface;

    // Rest API services

    /**
     * Add Donmo donation to quote
     *
     * @param string $cartId (masked quote ID)
     * @param float $donationAmount
     * @return string
     */
    public function addDonationToQuote(string $cartId, float $donationAmount): string;

    /**
    * Remove Donmo donation from quote
    *
    * @api
    * @param string $cartId (masked quote ID)
    * @return string
    */
    public function removeDonationFromQuote(string $cartId) : string;
}
