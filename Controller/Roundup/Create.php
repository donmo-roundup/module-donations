<?php

namespace Donmo\Roundup\Controller\Roundup;

use Donmo\Roundup\Logger\Logger;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Controller\ResultInterface;

class Create implements HttpPostActionInterface
{
    private Logger $logger;
    private ResultFactory $resultFactory;
    private Request $request;
    private CheckoutSession $checkoutSession;
    private CartRepositoryInterface $cartRepository;

    public function __construct(
        ResultFactory $resultFactory,
        Request $request,
        CheckoutSession $checkoutSession,
        CartRepositoryInterface $cartRepository,
        Logger $logger
    ) {
        $this->logger = $logger;
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
    }

    public function execute(): ResultInterface
    {
        // Add Donation to quote
        $jsonResponse = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $donationAmount = floatval($this->request->getBodyParams()['amount']);

        if ($this->checkoutSession->hasQuote()) {
            $quote = $this->checkoutSession->getQuote();

            $quote->setDonmodonation($donationAmount)->collectTotals();
            $this->cartRepository->save($quote);

            $jsonResponse->setData(
                [
                    'message' => 'success',
                ]
            );
        } else {
            $jsonResponse->setHttpResponseCode(404);

            $jsonResponse->setData(
                ['message' => 'no quote provided']
            );
        }

        return $jsonResponse;
    }
}
