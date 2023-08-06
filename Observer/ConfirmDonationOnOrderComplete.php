<?php

namespace Donmo\Roundup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Donmo\Roundup\Model\Donmo\DonationFactory;
use Donmo\Roundup\Model\Donmo\Donation as DonationModel;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;

use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Donmo\Roundup\Logger\Logger;
use Magento\Sales\Model\Order;

class ConfirmDonationOnOrderComplete implements ObserverInterface
{
    private DonationResource $donationResource;
    private Logger $logger;
    private QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId;

    public function __construct(
        Logger $logger,
        DonationFactory  $donationFactory,
        DonationResource $donationResource,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId
    ) {
        $this->logger = $logger;
        $this->donationFactory = $donationFactory;
        $this->donationResource = $donationResource;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
    }


    public function execute(Observer $observer): void
    {
        try {
            /* @var Order $order */
            $order = $observer->getEvent()->getData('order');

            /* @var float $donationAmount */
            $donationAmount = $order->getData('donmodonation');

            if ($order->getState() == 'complete') {
                if ($donationAmount > 0) {
                    $orderId = $order->getId();
                    $quoteId = $order->getQuoteId();
                    $maskedId = $this->quoteIdToMaskedQuoteId->execute($quoteId);

                    $donationModel = $this->donationFactory->create();

                    $this->donationResource->load($donationModel, $maskedId, 'masked_quote_id');

                    $donationModel
                        ->setStatus(DonationModel::STATUS_CONFIRMED)
                        ->setOrderId($orderId);
                    $this->donationResource->save($donationModel);
                }

            }
        } catch (\Exception $exception) {
            $this->logger->error("Donmo ConfirmDonationOnOrderComplete Observer error:\n" . $exception);
        }
    }
}
