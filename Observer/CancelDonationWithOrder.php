<?php

namespace Donmo\Roundup\Observer;

use Donmo\Roundup\Logger\Logger;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

use Donmo\Roundup\Api\DonationManagementInterface;
use Donmo\Roundup\Api\DonationRepositoryInterface;
use Donmo\Roundup\lib\ApiService;

class CancelDonationWithOrder implements ObserverInterface
{
    private Logger $logger;
    private ApiService $apiService;
    private DonationManagementInterface $donationManagement;

    private DonationRepositoryInterface $donationRepository;

    public function __construct(
        Logger $logger,
        ApiService $apiService,
        DonationManagementInterface $donationManagement,
        DonationRepositoryInterface $donationRepository
    ) {
        $this->logger = $logger;
        $this->apiService = $apiService;
        $this->donationManagement = $donationManagement;
        $this->donationRepository = $donationRepository;
    }

    public function execute(Observer $observer): void
    {
        try {
            $order = $observer->getEvent()->getOrder();

            $donationAmount = (float) $order->getData('donmodonation');

            if ($donationAmount) {
                $donation = $this->donationManagement->getByOrder($order);
                $donationMode = $donation->getMode();
                $donationMaskedId = $donation->getMaskedQuoteId();
                $donationStatus = $donation->getStatus();

                if ($donationStatus != DonationManagementInterface::STATUS_SENT) {
                    $donation->setStatus(DonationManagementInterface::STATUS_CANCELED);
                    $this->donationRepository->save($donation);
                }
                // make Delete request to Donmo API only if donation is already in SENT status,
                // then cancel it locally if the deletion was successful
                else {
                    $status = $this->apiService->deleteDonation($donationMode, $donationMaskedId);

                    if ($status == 200) {
                        $donation->setStatus(DonationManagementInterface::STATUS_CANCELED);
                        $this->donationRepository->save($donation);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error("Donmo CancelDonationWithOrder Observer error:\n" . $e);
        }
    }
}
