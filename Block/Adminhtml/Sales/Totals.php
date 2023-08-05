<?php

namespace Donmo\Roundup\Block\Adminhtml\Sales;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Donmo\Roundup\Model\Config;

class Totals extends Template
{

    private Context $context;

    private Config $config;

    public function __construct(
        Config $config,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->context = $context;
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
                'code' => 'donmodonation',
                'value' => $this->getOrder()->getDonmodonation(),
                'label' => $this->config->getDonationLabel(),
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
