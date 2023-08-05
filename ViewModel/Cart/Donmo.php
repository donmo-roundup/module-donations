<?php

namespace Donmo\Roundup\ViewModel\Cart;

use Donmo\Roundup\Model\Config as DonmoConfig;
use Magento\Framework\App\State;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Donmo implements ArgumentInterface
{
    private State $appState;
    private mixed $mode;
    private DonmoConfig $donmoConfig;
    private Json $json;
    public function __construct(State $appState, DonmoConfig $donmoConfig, Json $json)
    {
        $this->appState = $appState;
        $this->donmoConfig = $donmoConfig;
        $this->mode = $this->donmoConfig->getCurrentMode();
        $this->json = $json;
    }

    public function getIsAvailable()
    {
        $isActive = $this->donmoConfig->getIsActive();

        $modesCompatible =
            $this->appState->getMode() == State::MODE_PRODUCTION && $this->mode == 'live'
            ||
            $this->appState->getMode() == State::MODE_DEVELOPER && $this->mode == 'test';

        return ($isActive && $modesCompatible);
    }

    public function getDonmoConfig(): string
    {
        return
        $this->json->serialize([
            'publicKey' => $this->donmoConfig->getPublicKey($this->mode),
            'language' => $this->donmoConfig->getLanguageCode(),
            'integrationTitle' => $this->donmoConfig->getIntegrationTitle(),
            'roundupMessage' => $this->donmoConfig->getRoundupMessage(),
            'thankMessage' => $this->donmoConfig->getThankMessage(),
            'errorMessage' => $this->donmoConfig->getErrorMessage()
        ]);
    }
}
