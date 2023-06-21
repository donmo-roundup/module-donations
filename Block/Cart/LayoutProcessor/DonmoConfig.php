<?php

namespace Donmo\Roundup\Block\Cart\LayoutProcessor;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\App\State;
use Donmo\Roundup\Model\Config;

class DonmoConfig implements LayoutProcessorInterface
{
    private State $appState;
    private Config $config;
    private string $mode;
    private bool $isActive;


    public function __construct(State $appState, Config $config)
    {
        $this->appState = $appState;
        $this->config = $config;
        $this->mode = $this->config->getCurrentMode();
        $this->isActive = $this->config->getIsActive();
    }

    public function process($jsLayout)
    {
        $modesCompatible =
            $this->appState->getMode() == State::MODE_PRODUCTION && $this->mode == 'live'
            ||
            $this->appState->getMode() == State::MODE_DEVELOPER && $this->mode == 'test';

        if ($this->isActive && $modesCompatible) {
            // Set system.xml donationLabel value
            $jsLayout['components']['block-totals']['children']
                     ['donmodonation']['donmoConfig'] =
                     ['donationLabel' => $this->config->getDonationLabel()];

        } else {
            $jsLayout['components']['block-totals']['children']
                     ['donmodonation'] = [];
        }

        return $jsLayout;
    }
}
