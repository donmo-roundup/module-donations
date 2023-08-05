<?php

namespace Donmo\Roundup\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Locale\Resolver as LocaleResolver;

class Config
{
    const MODE_CONFIG_NAME = "donmo_roundup/donmo/donmo_mode";
    const IS_ACTIVE_CONFIG_NAME = "donmo_roundup/donmo/is_active";
    const INTEGRATION_TITLE_CONFIG_NAME = "donmo_roundup/donmo/integration_title";
    const ROUNDUP_MESSAGE_CONFIG_NAME = "donmo_roundup/donmo/roundup_message";
    const THANK_MESSAGE_CONFIG_NAME = "donmo_roundup/donmo/thank_message";
    const DONATION_SUMMARY_LABEL_CONFIG_NAME = "donmo_roundup/donmo/donation_summary_label";
    const ERROR_MESSAGE_CONFIG_NAME = "donmo_roundup/donmo/error_message";

    private ScopeConfigInterface $scopeConfig;
    private LocaleResolver $localeResolver;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig, LocaleResolver $localeResolver)
    {
        $this->scopeConfig = $scopeConfig;
        $this->localeResolver = $localeResolver;
    }

    public function getCurrentMode(): string
    {
        return $this->scopeConfig->getValue(self::MODE_CONFIG_NAME);
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
        return $this->scopeConfig->getValue(self::IS_ACTIVE_CONFIG_NAME);
    }
    public function getIntegrationTitle(): string
    {
        return $this->scopeConfig->getValue(self::INTEGRATION_TITLE_CONFIG_NAME);
    }

    public function getRoundupMessage(): string
    {
        return $this->scopeConfig->getValue(self::ROUNDUP_MESSAGE_CONFIG_NAME);
    }

    public function getThankMessage(): string
    {
        return $this->scopeConfig->getValue(self::THANK_MESSAGE_CONFIG_NAME);
    }

    public function getDonationSummaryLabel(): string
    {
        return $this->scopeConfig->getValue(self::DONATION_SUMMARY_LABEL_CONFIG_NAME);
    }

    public function getErrorMessage(): string
    {
        return $this->scopeConfig->getValue(self::ERROR_MESSAGE_CONFIG_NAME);
    }

    public function getLanguageCode() : string
    {
        $currentLocaleCode = $this->localeResolver->getLocale(); // uk_UA
        $languageCode = strstr($currentLocaleCode, '_', true);
        return $languageCode;
    }
}
