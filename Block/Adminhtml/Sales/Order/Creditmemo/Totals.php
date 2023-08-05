<?php

namespace Donmo\Roundup\Block\Adminhtml\Sales\Order\Creditmemo;

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
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }
    public function initTotals()
    {
        if ($this->getSource()->getDonmodonation() == 0) {
            return $this;
        }
        $total = new DataObject(
            [
                'code' => Donation::DONATION_CODE,
                'value' => $this->getSource()->getDonmodonation(),
                'label' => $this->donmoConfig->getDonationSummaryLabel()
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
