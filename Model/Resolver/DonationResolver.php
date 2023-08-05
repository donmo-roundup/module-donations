<?php

namespace Donmo\Roundup\Model\Resolver;

use Donmo\Roundup\Logger\Logger;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Model\Quote;

class DonationResolver implements ResolverInterface
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        /** @var Quote $quote */
        $quote = $value['model'];

        $donmoDonation = $quote->getDonmodonation();

        $currency = $quote->getQuoteCurrencyCode();

        return ['value' => $donmoDonation, 'currency' => $currency];
    }
}
