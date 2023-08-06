<?php

namespace Donmo\Roundup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\DataObject\Copy;
use Donmo\Roundup\Logger\Logger;

use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

class AddDonationToOrder implements ObserverInterface
{
    private Logger $logger;
    protected Copy $objectCopyService;

    public function __construct(
        Logger $logger,
        Copy $objectCopyService
    ) {
        $this->logger = $logger;
        $this->objectCopyService = $objectCopyService;
    }

    // Save Donmo donation amount before placing the order
    public function execute(Observer $observer)
    {
        try {
            /* @var Order $order */
            $order = $observer->getEvent()->getData('order');

            /* @var Quote $quote */
            $quote = $observer->getEvent()->getData('quote');

            $this->objectCopyService->copyFieldsetToTarget('sales_convert_quote', 'to_order', $quote, $order);

            return $this;

        } catch (\Exception $exception) {
            $this->logger->error("Donmo AddDonationToOrder Observer error:\n" . $exception);
        }
    }
}
