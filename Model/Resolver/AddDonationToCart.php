<?php

namespace Donmo\Roundup\Model\Resolver;

use Donmo\Roundup\Logger\Logger;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Quote\Api\CartRepositoryInterface;

use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;


class AddDonationToCart implements ResolverInterface
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
        $donationAmount = $args['input']['donation_amount'];
        if (!is_numeric($donationAmount) || $donationAmount < 0) {
            throw new GraphQlInputException(__('Invalid donation'));
        }

        $maskedCartId = $args['input']['cart_id'];
        $currentUserId = $context->getUserId();
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart = $this->getCartForUser->execute($maskedCartId, $currentUserId, $storeId);

        $cart->setDonmodonation($donationAmount)->collectTotals();
        $this->cartRepository->save($cart);

        return ['message' => 'success'];
    }
}
