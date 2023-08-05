<?php

namespace Donmo\Roundup\Block\Adminhtml\Sales\Order\Creditmemo;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Donmo\Roundup\Model\Config;

class Totals extends Template
{
    private Config $config;
    private Context $context;

    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->context = $context;
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
                'code' => 'donmodonation',
                'value' => $this->getSource()->getDonmodonation(),
                'label' => $this->config->getDonationLabel()
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
