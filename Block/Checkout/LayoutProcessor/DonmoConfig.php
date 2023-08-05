<?php

namespace Donmo\Roundup\Block\Checkout\LayoutProcessor;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Donmo\Roundup\Model\Config;

class DonmoConfig implements LayoutProcessorInterface
{
    private Config $donmoConfig;

    private string $mode;
    private bool $isActive;

    public function __construct(Config $donmoConfig)
    {
        $this->donmoConfig = $donmoConfig;

        $this->mode = $this->donmoConfig->getCurrentMode();
        $this->isActive = $this->donmoConfig->getIsActive();
    }


    public function process($jsLayout)
    {
        if ($this->isActive) {
            // Set dynamic system.xml values to jsLayout donmoConfig
            $jsLayout['components']['checkout']['children']
            ['steps']['children']
            ['billing-step']['children']
            ['payment']['children']['afterMethods']
            ['children']['donmo-block']['donmoConfig'] = [
                'publicKey' => $this->donmoConfig->getPublicKey($this->mode),
                'language' => $this->donmoConfig->getLanguageCode(),
                'integrationTitle' => $this->donmoConfig->getIntegrationTitle(),
                'roundupMessage' => $this->donmoConfig->getRoundupMessage(),
                'thankMessage' => $this->donmoConfig->getThankMessage(),
                'errorMessage' => $this->donmoConfig->getErrorMessage(),
            ];

            // Set system.xml donationLabel value
            $jsLayout['components']['checkout']['children']
            ['sidebar']['children']['summary']
            ['children']['totals']['children']
            ['donmodonation']['donmoConfig'] =
                ['donationLabel' => $this->donmoConfig->getDonationSummaryLabel()];


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
