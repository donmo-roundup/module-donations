<?php

namespace Donmo\Roundup\Block\Sales\Order;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Donmo\Roundup\Model\Config as DonmoConfig;

class Donmodonation extends Template
{
    protected $order;
    private DonmoConfig $donmoConfig;


    public function __construct(
        DonmoConfig $donmoConfig,
        Context $context,
        array $data = []
    ) {
        $this->donmoConfig = $donmoConfig;
        parent::__construct($context, $data);
    }

    public function displayFullSummary()
    {
        return true;
    }

    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    public function initTotals()
    {
        $this->order = $this->getParentBlock()->getOrder();

        if ($this->order->getDonmodonation() == 0) {
            return $this;
        }
        $total = new DataObject(
            [
                'code' => 'donmodonation',
                'value' => $this->getOrder()->getDonmodonation(),
                'label' => $this->donmoConfig->getDonationSummaryLabel(),
            ]
        );
        $this->getParentBlock()->addTotal($total, 'subtotal');

        return $this;
    }
}
