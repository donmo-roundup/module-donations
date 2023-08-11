<?php

namespace Donmo\Roundup\Model\Resolver;

use Donmo\Roundup\Logger\Logger;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;

class RemoveDonationFromCart implements ResolverInterface
{
    private Logger $logger;
    private CartRepositoryInterface $cartRepository;
    private GetCartForUser $getCartForUser;

    public function __construct(
        Logger $logger,
        CartRepositoryInterface $cartRepository,
        GetCartForUser $getCartForUser
    ) {
        $this->logger = $logger;
        $this->cartRepository = $cartRepository;
        $this->getCartForUser = $getCartForUser;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        $maskedCartId = $args['cart_id'];
        $currentUserId = $context->getUserId();
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart = $this->getCartForUser->execute($maskedCartId, $currentUserId, $storeId);

        $cart->setDonmodonation(0)->collectTotals();
        $this->cartRepository->save($cart);

        return ['message' => 'success'];
    }
}
