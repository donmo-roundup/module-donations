<?php

namespace Donmo\Roundup\Model\Total;

use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Donmo\Roundup\Model\Config as DonmoConfig;

class Donation extends AbstractTotal
{

    private DonmoConfig $donmoConfig;

    public function __construct(DonmoConfig $donmoConfig)
    {
        $this->donmoConfig = $donmoConfig;
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ): static {
        parent::collect($quote, $shippingAssignment, $total);
        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $donationAmount = $quote->getDonmodonation();

        $total->setTotalAmount('donmodonation', $donationAmount);

        $total->setBaseTotalAmount('donmodonation', $donationAmount);

        return $this;
    }

    public function fetch(Quote $quote, Total $total): array
    {

        $donationAmount = $quote->getDonmodonation();
        if ($donationAmount) {
            return [
                'code' => 'donmodonation',
                'title' => $this->donmoConfig->getDonationLabel(),
                'value' => $donationAmount
            ];
        } else {
            return [];
        }
    }
}
