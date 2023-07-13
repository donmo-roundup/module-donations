<?php

namespace Donmo\Roundup\Model\Resolver;

use Donmo\Roundup\Logger\Logger;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;

class RemoveDonationFromQuote implements ResolverInterface
{
    private Logger $logger;
    private CartRepositoryInterface $cartRepository;
    private MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId;


    public function __construct(
        Logger $logger,
        CartRepositoryInterface $cartRepository,
        MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
    ) {
        $this->logger = $logger;
        $this->cartRepository = $cartRepository;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
    }


    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $maskedId = $args['cartId'];

        $quoteId = $this->maskedQuoteIdToQuoteId->execute($maskedId);

        $quote = $this->cartRepository->get($quoteId);

        $quote->setDonmodonation(0)->collectTotals();
        $this->cartRepository->save($quote);

        return ['message' => 'success'];
    }
}
