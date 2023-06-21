<?php

namespace Donmo\Roundup\Controller\Roundup;

use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpDeleteActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;

class Remove implements HttpDeleteActionInterface
{

    public function __construct(
        ResultFactory $resultFactory,
        Request $request,
        Quote $quote,
        Session $checkoutSession,
        CartRepositoryInterface $quoteRepository,
        CustomerCart $cart
    )
    {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->quote = $quote;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->cart = $cart;
    }
    public function execute()
    {
        // Remove Donation from quote
        $jsonResponse = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $cartQuote = $this->cart->getQuote();
        $cartQuote->setDonmodonation(0)->collectTotals();
        $this->quoteRepository->save($cartQuote);
        $this->checkoutSession->setCartGrandTotal($cartQuote->getGrandTotal());

        $total = $cartQuote->getGrandTotal();
        $jsonResponse->setData(
            [
                'total' => $total
            ]
        );
        return $jsonResponse;
    }
}
