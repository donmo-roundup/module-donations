<?php

namespace Donmo\Roundup\ViewModel\Cart;

use Donmo\Roundup\Model\Config as DonmoConfig;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Donmo implements ArgumentInterface
{
    private string $mode;
    private DonmoConfig $donmoConfig;
    private Json $json;
    public function __construct(DonmoConfig $donmoConfig, Json $json)
    {
        $this->donmoConfig = $donmoConfig;
        $this->mode = $this->donmoConfig->getCurrentMode();
        $this->json = $json;
    }

    public function getIsActive(): bool
    {
        return $this->donmoConfig->getIsActive();
    }

    public function getDonmoConfig(): string
    {
        return
        $this->json->serialize([
            'publicKey' => $this->donmoConfig->getPublicKey($this->mode),
            'language' => $this->donmoConfig->getLanguageCode(),
            'currency' => $this->donmoConfig->getCurrencyCode(),
            'integrationTitle' => $this->donmoConfig->getIntegrationTitle(),
            'roundupMessage' => $this->donmoConfig->getRoundupMessage(),
            'thankMessage' => $this->donmoConfig->getThankMessage(),
            'errorMessage' => $this->donmoConfig->getErrorMessage()
        ]);
    }
}
