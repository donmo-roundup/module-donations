<?php
namespace Donmo\Roundup\Model;

use Magento\Framework\Model\AbstractModel;
use Donmo\Roundup\Api\Data\DonationInterface;
use Donmo\Roundup\Model\ResourceModel\Donation as DonationResourceModel;

class Donation extends AbstractModel implements DonationInterface
{
    protected $_eventPrefix = 'donmo_donation';

    protected function _construct()
    {
        $this->_init(DonationResourceModel::class);
    }

    /**
     * Get Donation ID
     *
     * @return int
     */
    public function getDonationId(): int
    {
        return $this->getData(self::DONATION_ID);
    }

    /**
     * Set Donation ID
     *
     * @param int $donationId
     * @return $this
     */
    public function setDonationId(int $donationId)
    {
        return $this->setData(self::DONATION_ID, $donationId);
    }

    /**
     * Get Donation Amount
     *
     * @return float
     */
    public function getDonationAmount(): float
    {
        return $this->getData(self::DONATION_AMOUNT);
    }

    /**
     * Set Donation Amount
     *
     * @param float $donationAmount
     * @return $this
     */
    public function setDonationAmount($donationAmount)
    {
        return $this->setData(self::DONATION_AMOUNT, $donationAmount);
    }

    /**
     * Get Currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->getData(self::DONATION_CURRENCY);
    }

    /**
     * Set Status
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        return $this->setData(self::DONATION_CURRENCY, $currency);
    }

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set Status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get Mode
     *
     * @return string
     */
    public function getMode(): string
    {
        return $this->getData(self::MODE);
    }

    /**
     * Set Mode
     *
     * @param string $mode
     * @return $this
     */
    public function setMode($mode)
    {
        return $this->setData(self::MODE, $mode);
    }

    /**
     * Get Masked Quote ID
     *
     * @return string
     */
    public function getMaskedQuoteId(): string
    {
        return $this->getData(self::MASKED_QUOTE_ID);
    }

    /**
     * Set Masked Quote ID
     *
     * @param string $maskedQuoteId
     * @return $this
     */
    public function setMaskedQuoteId($maskedQuoteId)
    {
        return $this->setData(self::MASKED_QUOTE_ID, $maskedQuoteId);
    }

    /**
     * Get Order ID
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Set Order ID
     *
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Get Created At Timestamp
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set Created At Timestamp
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get Updated At Timestamp
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set Updated At Timestamp
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
