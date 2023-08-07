<?php

namespace Donmo\Roundup\Observer;

use Donmo\Roundup\Logger\Logger;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use Donmo\Roundup\Api\DonationManagementInterface;
use Donmo\Roundup\Api\DonationRepositoryInterface;

class ConfirmDonationOnOrderComplete implements ObserverInterface
{
    private Logger $logger;
    private DonationManagementInterface $donationManagement;
    private DonationRepositoryInterface $donationRepository;
    public function __construct(
        Logger $logger,
        DonationManagementInterface $donationManagement,
        DonationRepositoryInterface $donationRepository
    ) {
        $this->logger = $logger;
        $this->donationManagement = $donationManagement;
        $this->donationRepository = $donationRepository;
    }

    public function execute(Observer $observer): void
    {
        try {
            /* @var Order $order */
            $order = $observer->getEvent()->getData('order');

            /* @var float $donationAmount */
            $donationAmount = $order->getData('donmodonation');

            if ($order->getState() == 'complete' and $donationAmount) {
                $donation = $this->donationManagement->getByOrder($order);

                $donation->setStatus(DonationManagementInterface::STATUS_CONFIRMED);
                $this->donationRepository->save($donation);
            }
        } catch (\Exception $exception) {
            $this->logger->error("Donmo ConfirmDonationOnOrderComplete Observer error:\n" . $exception);
        }
    }
}
