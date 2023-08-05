<?php

namespace Donmo\Roundup\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

class Donation extends AbstractTotal
{
    public function collect(Creditmemo $creditmemo): Donation
    {
        $creditmemo->setDonmodonation(0);

        $amount = $creditmemo->getOrder()->getDonmodonation();

        $creditmemo->setDonmodonation($amount);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $creditmemo->getDonmodonation());
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $creditmemo->getDonmodonation());

        return $this;
    }
}
