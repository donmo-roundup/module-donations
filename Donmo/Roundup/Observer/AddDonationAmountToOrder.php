<?php

namespace Donmo\Roundup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddDonationAmountToOrder implements ObserverInterface
{
    // Save Donmo donation amount before placing the order
    public function execute(Observer $observer)
    {
        try {
            $quote = $observer->getQuote();

            $donation = $quote->getDonmodonation();
            if (!$donation) {
                return $this;
            }

            $order = $observer->getOrder();
            $order->setData('donmodonation', $donation);
        } catch (\Exception $exception) {
            $this->logger->error("Donmo AddDonationAmountToOrder Observer error:\n" . $exception);
        }
    }
}
