<?php

namespace Donmo\Roundup\Helper;

use Magento\Framework\Locale\Resolver;

class Locale
{
    public function __construct(
        Resolver $localeResolver,
    ) {
        $this->localeResolver = $localeResolver;
    }

    public function getLanguageCode() {
        $currentLocaleCode = $this->localeResolver->getLocale(); // fr_CA
        $languageCode = strstr($currentLocaleCode, '_', true);
        return $languageCode;
    }

}
