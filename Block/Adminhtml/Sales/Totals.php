<?php

namespace Donmo\Roundup\Block\Adminhtml\Sales;

use Magento\Framework\View\Element\Template;

use Magento\Framework\View\Element\Template\Context;
use Donmo\Roundup\Model\Config as DonmoConfig;
use Magento\Framework\DataObject;

use Donmo\Roundup\Model\Total\Donation;

class Totals extends Template
{
    private DonmoConfig $donmoConfig;

    public function __construct(
        Context $context,
        DonmoConfig $donmoConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->donmoConfig = $donmoConfig;
    }

    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function initTotals()
    {
        $this->order = $this->getParentBlock()->getOrder();

        if ($this->order->getDonmodonation()== 0) {
            return $this;
        }
        $total = new DataObject(
            [
                'code' => Donation::DONATION_CODE,
                'value' => $this->getOrder()->getDonmodonation(),
                'label' => $this->donmoConfig->getDonationSummaryLabel(),
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
