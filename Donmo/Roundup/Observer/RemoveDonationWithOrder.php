<?php

namespace Donmo\Roundup\Observer;

use Donmo\Roundup\lib\ApiService;
use Donmo\Roundup\Logger\Logger;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Donmo\Roundup\Model\Donmo\DonationFactory;
use Donmo\Roundup\Model\Donmo\Donation as DonationModel;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;


use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;

class RemoveDonationWithOrder implements ObserverInterface
{
    private Logger $logger;
    private DonationFactory $donationFactory;
    private DonationResource $donationResource;
    private QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId;

    private ApiService $apiService;
    public function __construct(
        Logger $logger,
        DonationFactory  $donationFactory,
        DonationResource $donationResource,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId,
        ApiService $apiService
    )
    {
        $this->logger = $logger;
        $this->donationFactory = $donationFactory;
        $this->donationResource = $donationResource;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
        $this->apiService = $apiService;
    }

    public function execute(Observer $observer)
    {
        try {
            $order = null;
            $newDonationStatus = DonationModel::STATUS_DELETED;
            $eventName = $observer->getEvent()->getName();

            if ($eventName == 'order_cancel_after') {
                $order = $observer->getEvent()->getOrder();
            }

            if ($eventName == 'sales_order_creditmemo_refund') {
                $order = $observer->getEvent()->getCreditmemo()->getOrder();
                $newDonationStatus = DonationModel::STATUS_REFUNDED;
            }

            if ($order->getDonmodonation() > 0) {
                $quoteId = $order->getQuoteId();
                $maskedId = $this->quoteIdToMaskedQuoteId->execute($quoteId);

                $donationModel = $this->donationFactory->create();
                $this->donationResource->load($donationModel, $maskedId, 'masked_quote_id');
                $donationMode = $donationModel->getData('mode');

                if ($donationModel->getData('status') != DonationModel::STATUS_SENT) {
                    $donationModel->setData('status', $newDonationStatus);
                    $this->donationResource->save($donationModel);
                } // make Delete request to Donmo API for only if donation is already sent
                else {
                    $status = $this->apiService->deleteDonation($donationMode, $maskedId);

                    if ($status == 200) {
                        $donationModel->setData('status', $newDonationStatus);
                        $this->donationResource->save($donationModel);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error("Donmo RemoveDonationWithOrder Observer error:\n" . $e);
        }
    }

}
