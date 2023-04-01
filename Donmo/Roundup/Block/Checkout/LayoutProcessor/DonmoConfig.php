<?php

namespace Donmo\Roundup\Block\Checkout\LayoutProcessor;

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

        if($this->isActive && $modesCompatible) {
            // Set dynamic system.xml values to jsLayout donmoConfig
            $jsLayout['components']['checkout']['children']
            ['steps']['children']
            ['billing-step']['children']
            ['payment']['children']['afterMethods']
            ['children']['donmo-block']['donmoConfig'] = [
                'publicKey' => $this->config->getPublicKey($this->mode),
                'isBackendBased' => $this->config->getIsBackendBased(),
                'integrationTitle' => $this->config->getIntegrationTitle(),
                'donateMessage' => $this->config->getDonateMessage(),
                'thankMessage' => $this->config->getThankMessage(),
                'errorMessage' => $this->config->getErrorMessage(),
            ];

            // Set system.xml donationLabel value
            $jsLayout['components']['checkout']['children']
            ['sidebar']['children']['summary']
            ['children']['totals']['children']
            ['donmodonation']['donmoConfig'] =
                ['donationLabel' => $this->config->getDonationLabel()];


        } else {
            $jsLayout['components']['checkout']['children']
            ['steps']['children']
            ['billing-step']['children']
            ['payment']['children']['afterMethods']
            ['children']['donmo-block'] = [];

            $jsLayout['components']['checkout']['children']
            ['sidebar']['children']
            ['summary']['children']
            ['totals']['children']['donmodonation']= [];
        }

        return $jsLayout;
    }
}
