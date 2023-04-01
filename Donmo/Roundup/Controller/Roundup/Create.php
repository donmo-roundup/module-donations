<?php

namespace Donmo\Roundup\Controller\Roundup;

use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Quote\Model\Quote;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\CartRepositoryInterface;

class Create implements HttpPostActionInterface
{

    /**
     * @var Request
     */
    private $request;

    public function __construct(
        ResultFactory $resultFactory,
        Request $request,
        Quote $quote,
        Session $checkoutSession,
        CartRepositoryInterface $quoteRepository,
        CustomerCart $cart,
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->quote = $quote;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->cart = $cart;
    }


    public function execute()
    {
        // Add Donation to quote
        $jsonResponse = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $donationAmount = floatval($this->request->getBodyParams()['amount']);

        $cartQuote = $this->cart->getQuote();
        $cartQuote->setDonmodonation($donationAmount)->collectTotals();
        $this->quoteRepository->save($cartQuote);
        $this->checkoutSession->setCartGrandTotal($cartQuote->getGrandTotal());

        $jsonResponse->setData(
            [
                'status' => 'success',
                'donationAmount' => $donationAmount
            ]
        );
        return $jsonResponse;
    }
}
