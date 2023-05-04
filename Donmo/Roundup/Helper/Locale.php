<?php

namespace Donmo\Roundup\Helper;

use Magento\Framework\Locale\Resolver;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Locale
{
    public function __construct(
        Resolver $localeResolver,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->localeResolver = $localeResolver;
        $this->priceCurrency = $priceCurrency;
    }

    public function getLanguageCode() {
        $currentLocaleCode = $this->localeResolver->getLocale(); // fr_CA
        $languageCode = strstr($currentLocaleCode, '_', true);
        return $languageCode;
    }

    public function getCurrencySymbol() {
        return $this->priceCurrency->getCurrencySymbol();
    }

}
