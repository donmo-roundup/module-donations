<?php

namespace Donmo\Roundup\Observer;

use Donmo\Roundup\Logger\Logger;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Donmo\Roundup\Api\DonationManagementInterface;
use Magento\Sales\Model\Order;

class CreateDonation implements ObserverInterface
{
    private Logger $logger;

    private DonationManagementInterface $donationManagement;
    public function __construct(
        Logger $logger,
        DonationManagementInterface $donationManagement
    ) {
        $this->logger = $logger;
        $this->donationManagement = $donationManagement;
    }


    public function execute(Observer $observer): void
    {
        /* @var Order $order */
        $order = $observer->getEvent()->getOrder();

        try {
            $this->donationManagement->createDonation($order);
        } catch (\Exception $exception) {
            $this->logger->error("Donmo CreateDonation Observer error:\n" . $exception);
        }
    }
}
