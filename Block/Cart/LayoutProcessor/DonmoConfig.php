<?php

namespace Donmo\Roundup\Block\Cart\LayoutProcessor;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Donmo\Roundup\Model\Config;

class DonmoConfig implements LayoutProcessorInterface
{
    private Config $donmoConfig;
    private bool $isActive;


    public function __construct(Config $donmoConfig)
    {
        $this->donmoConfig = $donmoConfig;
        $this->isActive = $this->donmoConfig->getIsActive();
    }

    public function process($jsLayout)
    {
        if ($this->isActive) {
            // Set system.xml donationLabel value
            $jsLayout['components']['block-totals']['children']
                     ['donmodonation']['donmoConfig'] =
                     ['donationLabel' => $this->donmoConfig->getDonationSummaryLabel()];

        } else {
            $jsLayout['components']['block-totals']['children']
                     ['donmodonation'] = [];
        }

        return $jsLayout;
    }
}
