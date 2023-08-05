<?php

namespace Donmo\Roundup\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Donation extends AbstractTotal
{
    public function collect(Invoice $invoice): Donation
    {
        $invoice->setDonmodonation(0);

        $amount = $invoice->getOrder()->getDonmodonation();

        $invoice->setDonmodonation($amount);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $invoice->getDonmodonation());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $invoice->getDonmodonation());

        return $this;
    }
}
