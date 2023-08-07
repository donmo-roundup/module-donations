<?php

namespace Donmo\Roundup\Api\Data;

/**
 * Interface DonationInterface
 * @package YourVendor\YourModule\Api\Data
 */
interface DonationInterface
{
    /**
     * Constants for keys of data array
     */
    const DONATION_ID = 'donation_id';
    const DONATION_AMOUNT = 'donation_amount';
    const STATUS = 'status';
    const MODE = 'mode';
    const MASKED_QUOTE_ID = 'masked_quote_id';
    const ORDER_ID = 'order_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const DONATION_CODE = 'donmodonation';
    /**
     * Get Donation ID
     *
     * @return int
     */
    public function getDonationId(): int;

    /**
     * Set Donation ID
     *
     * @param int $donationId
     * @return $this
     */
    public function setDonationId(int $donationId);

    /**
     * Get Donation Amount
     *
     * @return float
     */
    public function getDonationAmount(): float;

    /**
     * Set Donation Amount
     *
     * @param float $donationAmount
     * @return $this
     */
    public function setDonationAmount(float $donationAmount);

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set Status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status);

    /**
     * Get Mode
     *
     * @return string
     */
    public function getMode(): string;

    /**
     * Set Mode
     *
     * @param string $mode
     * @return $this
     */
    public function setMode(string $mode);

    /**
     * Get Masked Quote ID
     *
     * @return string|null
     */
    public function getMaskedQuoteId();

    /**
     * Set Masked Quote ID
     *
     * @param string $maskedQuoteId
     * @return $this
     */
    public function setMaskedQuoteId(string $maskedQuoteId);

    /**
     * Get Order ID
     *
     * @return int|null
     */
    public function getOrderId();

    /**
     * Set Order ID
     *
     * @param int $orderId
     * @return $this
     */
    public function setOrderId(int $orderId);

    /**
     * Get Created At
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt);

    /**
     * Get Updated At
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt);
}
