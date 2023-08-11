<?php

namespace Donmo\Roundup\Model;

use Donmo\Roundup\Api\GuestCartDonationManagementInterface;
use Donmo\Roundup\Logger\Logger;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;
use Donmo\Roundup\Api\CartDonationManagementInterface;

class GuestCartDonationManagement implements GuestCartDonationManagementInterface
{
    private Logger $logger;
    private MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId;
    private CartDonationManagementInterface $cartDonationManagement;

    public function __construct(
        Logger $logger,
        MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId,
        CartDonationManagementInterface $cartDonationManagement
    ) {
        $this->logger = $logger;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
        $this->cartDonationManagement = $cartDonationManagement;
    }

    /**
     * @inheritdoc
     */
    public function addDonationToCart(string $cartId, float $donationAmount): string
    {
        $this->logger->info('addDonationToCart guest');
        return $this->cartDonationManagement->addDonationToCart($this->maskedQuoteIdToQuoteId->execute($cartId), $donationAmount);
    }

    /**
     * @inheritdoc
     */
    public function removeDonationFromCart(string $cartId): string
    {
        return $this->cartDonationManagement->removeDonationFromCart($this->maskedQuoteIdToQuoteId->execute($cartId));
    }
}
