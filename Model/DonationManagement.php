<?php

namespace Donmo\Roundup\Model;

use Donmo\Roundup\Logger\Logger;
use Donmo\Roundup\Api\Data\DonationInterface;
use Donmo\Roundup\Api\Data\DonationInterfaceFactory;
use Donmo\Roundup\Api\DonationManagementInterface;
use Donmo\Roundup\Api\DonationRepositoryInterface;
use Donmo\Roundup\Model\Config as DonmoConfig;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Magento\Sales\Model\Order;
use Donmo\Roundup\Model\ResourceModel\Donation as DonationResourceModel;

class DonationManagement implements DonationManagementInterface
{
    private Logger $logger;
    private DonationInterfaceFactory $donationFactory;
    private QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId;
    private DonmoConfig $donmoConfig;
    private DonationRepositoryInterface $donationRepository;

    private DonationResourceModel $donationResource;
    public function __construct(
        Logger $logger,
        DonationInterfaceFactory        $donationFactory,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId,
        DonmoConfig                     $donmoConfig,
        DonationRepositoryInterface     $donationRepository,
        DonationResourceModel $donationResource
    ) {
        $this->logger = $logger;
        $this->donationFactory = $donationFactory;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
        $this->donmoConfig = $donmoConfig;
        $this->donationRepository = $donationRepository;
        $this->donationResource = $donationResource;
    }

    /**
     * @inheritdoc
     */
    public function createDonation(Order $order): void
    {
        $donationAmount = $order->getDonmodonation();

        $orderId = $order->getId();

        $quoteId = $order->getQuoteId();
        $maskedId = $this->quoteIdToMaskedQuoteId->execute($quoteId);
        $currentMode = $this->donmoConfig->getCurrentMode();

        if ($donationAmount) {
            $donation = $this->donationFactory->create();
            $donation
                ->setOrderId($orderId)
                ->setMaskedQuoteId($maskedId)
                ->setDonationAmount($donationAmount)
                ->setMode($currentMode)
                ->setStatus(self::STATUS_PENDING);

            $this->donationRepository->save($donation);
        }
    }

    /**
     * @inheritdoc
     */
    public function getByOrder(Order $order): DonationInterface
    {
        $orderId = $order->getId();
        $entity = $this->donationFactory->create();

        $this->donationResource->load($entity, $orderId, DonationInterface::ORDER_ID);

        if (!$entity->getEntityId()) {
            throw new NoSuchEntityException(__("The requested donation does not exist."));
        }

        return $entity;
    }
}
