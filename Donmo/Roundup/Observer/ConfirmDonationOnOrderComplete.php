<?php

namespace Donmo\Roundup\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Donmo\Roundup\Model\Donmo\DonationFactory;
use Donmo\Roundup\Model\Donmo\Donation as DonationModel;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;

use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Psr\Log\LoggerInterface;
class ConfirmDonationOnOrderComplete implements ObserverInterface
{
    private DonationResource $donationResource;
    private LoggerInterface $logger;
    private QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId;

    public function __construct(
        LoggerInterface $logger,
        DonationFactory  $donationFactory,
        DonationResource $donationResource,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId
    )
    {
        $this->logger = $logger;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
        $this->donationFactory = $donationFactory;
        $this->donationResource = $donationResource;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
    }


    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if($order->getState() == 'complete') {
            if ($order->getDonmodonation() > 0) {

                $quoteId = $order->getQuoteId();
                $maskedId = $this->quoteIdToMaskedQuoteId->execute($quoteId);

                $donationModel = $this->donationFactory->create();

                $this->donationResource->load($donationModel, $maskedId, 'masked_quote_id');

                $donationModel
                    ->setStatus(DonationModel::STATUS_CONFIRMED);
                $this->donationResource->save($donationModel);
            }

            try {
                $this->donationResource->save($donationModel);
            } catch (\Exception $exception) {
                $this->logger->error($exception);
            }
        }
    }
}
