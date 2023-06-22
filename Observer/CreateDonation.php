<?php

namespace Donmo\Roundup\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Donmo\Roundup\Model\Config as DonmoConfig;


use Donmo\Roundup\Model\Donmo\DonationFactory;
use Donmo\Roundup\Model\Donmo\Donation as DonationModel;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;

use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Donmo\Roundup\Logger\Logger;

class CreateDonation implements ObserverInterface
{
    private DonmoConfig $donmoConfig;
    private DonationFactory $donationFactory;
    private DonationResource $donationResource;


    private Logger $logger;
    private QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId;

    public function __construct(
        DonationFactory  $donationFactory,
        DonationResource $donationResource,
        Logger $logger,
        DonmoConfig $config,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId
    )
    {
        $this->donationFactory = $donationFactory;
        $this->donationResource = $donationResource;
        $this->logger = $logger;
        $this->donmoConfig = $config;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
    }


    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $donationAmount = (float) $order->getDonmodonation();

        try {
            if ($donationAmount > 0) {
                $quoteId = $order->getQuoteId();
                $maskedId = $this->quoteIdToMaskedQuoteId->execute($quoteId);
                $currentMode = $this->donmoConfig->getCurrentMode();

                $donationModel = $this->donationFactory->create();

                $donationModel
                    ->setMaskedQuoteId($maskedId)
                    ->setDonationAmount($donationAmount)
                    ->setStatus(DonationModel::STATUS_PENDING)
                    ->setMode($currentMode);

                $this->donationResource->save($donationModel);
            }
        } catch (\Exception $exception) {
            $this->logger->error("Donmo CreateDonation Observer error:\n" . $exception);
        }
    }
}
