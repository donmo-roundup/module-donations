<?php

namespace Donmo\Roundup\Model\Total;

use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Donmo\Roundup\Model\Config as DonmoConfig;

class Donation extends AbstractTotal
{
    const DONATION_CODE = 'donmodonation';

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
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $donationAmount = $quote->getDonmodonation();

        $total->setTotalAmount(self::DONATION_CODE, $donationAmount);

        $total->setBaseTotalAmount(self::DONATION_CODE, $donationAmount);

        return $this;
    }

    public function fetch(Quote $quote, Total $total): array
    {

        $donationAmount = $quote->getDonmodonation();
        if ($donationAmount) {
            return [
                'code' => self::DONATION_CODE,
                'title' => $this->donmoConfig->getDonationSummaryLabel(),
                'value' => $donationAmount
            ];
        } else {
            return [];
        }
    }
}
