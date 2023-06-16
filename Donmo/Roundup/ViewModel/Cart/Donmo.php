<?php

namespace Donmo\Roundup\ViewModel\Cart;
use Magento\Framework\App\State;
use Donmo\Roundup\Model\Config;
use \Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Donmo\Roundup\Helper\Locale;

class Donmo implements ArgumentInterface
{
    private Locale $locale;
    private State $appState;
    private mixed $mode;
    private Config $config;
    private Json $json;
    public function __construct(State $appState, Config $config, Json $json, Locale $locale)
    {
        $this->locale = $locale;
        $this->appState = $appState;
        $this->config = $config;
        $this->mode = $this->config->getCurrentMode();
        $this->json = $json;
    }

    public function getIsAvailable() {
        $isActive = $this->config->getIsActive();

        $modesCompatible =
            $this->appState->getMode() == State::MODE_PRODUCTION && $this->mode == 'live'
            ||
            $this->appState->getMode() == State::MODE_DEVELOPER && $this->mode == 'test';

        return ($isActive && $modesCompatible);
    }

    public function getDonmoConfig() {
        return
        $this->json->serialize([
            'publicKey' => $this->config->getPublicKey($this->mode),
            'language' => $this->locale->getLanguageCode(),
            'integrationTitle' => $this->config->getIntegrationTitle(),
            'roundupMessage' => $this->config->getRoundupMessage(),
            'thankMessage' => $this->config->getThankMessage(),
            'errorMessage' => $this->config->getErrorMessage()
        ]);
    }

}
