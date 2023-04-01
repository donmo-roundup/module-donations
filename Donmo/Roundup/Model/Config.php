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

    public function getCurrentMode()
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donmo_mode");
    }

    public function getSecretKey($mode)
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donmo_{$mode}_sk");
    }

    public function getPublicKey($mode)
    {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donmo_{$mode}_pk");
    }

    public function getIsActive() {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/is_active");
    }

    public function getIsBackendBased() {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/is_cron_enabled");
    }

    public function getIntegrationTitle() {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/integration_title");
    }

    public function getDonateMessage() {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donate_message");
    }

    public function getThankMessage() {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/thank_message");
    }

    public function getDonationLabel() {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/donation_label");
    }

    public function getErrorMessage() {
        return $this->scopeConfig->getValue("donmo_roundup/donmo/error_message");
    }

}
