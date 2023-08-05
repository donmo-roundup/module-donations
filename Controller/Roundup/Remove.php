<?php

namespace Donmo\Roundup\Controller\Roundup;

use Donmo\Roundup\Logger\Logger;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpDeleteActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Api\CartRepositoryInterface;

class Remove implements HttpDeleteActionInterface
{

    private Logger $logger;
    private ResultFactory $resultFactory;
    private Session $checkoutSession;
    private CartRepositoryInterface $cartRepository;

    public function __construct(
        ResultFactory $resultFactory,
        Session $checkoutSession,
        CartRepositoryInterface $cartRepository,
        Logger $logger
    ) {
        $this->resultFactory = $resultFactory;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
        $this->logger = $logger;
    }
    public function execute()
    {
        // Remove Donation from quote
        $jsonResponse = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if ($this->checkoutSession->hasQuote()) {
            $quote = $this->checkoutSession->getQuote();

            $quote->setDonmodonation(0)->collectTotals();
            $this->cartRepository->save($quote);

            $jsonResponse->setData(
                [
                    'message' => 'success'
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
