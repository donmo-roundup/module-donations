<?php

namespace Donmo\Roundup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddDonationToOrder implements ObserverInterface
{
    // Save Donmo donation before placing the order
    public function execute(Observer $observer)
    {
        $quote = $observer->getQuote();
        $donation = $quote->getDonmodonation();
        if (!$donation) {
            return $this;
        }

        $order = $observer->getOrder();
        $order->setData('donmodonation', $donation);
    }
}
