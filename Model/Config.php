<?php

namespace Donmo\Roundup\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private ScopeConfigInterface $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getCurrentMode(): string
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donmo_mode");
    }

    public function getSecretKey(string $mode) : string
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donmo_{$mode}_sk");
    }

    public function getPublicKey(string $mode)
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donmo_{$mode}_pk");
    }

    public function getIsActive() : bool
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/is_active");
    }
    public function getIntegrationTitle(): string
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/integration_title");
    }

    public function getRoundupMessage(): string
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donate_message");
    }

    public function getThankMessage(): string
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/thank_message");
    }

    public function getDonationLabel(): string
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donation_label");
    }

    public function getErrorMessage(): string
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/error_message");
    }
}
