<?php

namespace Donmo\Roundup\Model;

use Donmo\Roundup\Logger\Logger;
use Donmo\Roundup\Api\Data\DonationInterface;
use Donmo\Roundup\Api\Data\DonationInterfaceFactory;
use Donmo\Roundup\Api\DonationManagementInterface;
use Donmo\Roundup\Api\DonationRepositoryInterface;
use Donmo\Roundup\Model\Config as DonmoConfig;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Magento\Sales\Model\Order;
use Donmo\Roundup\Model\ResourceModel\Donation as DonationResourceModel;


use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;

class DonationManagement implements DonationManagementInterface
{
    private Logger $logger;
    private DonationInterfaceFactory $donationFactory;
    private QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId;
    private DonmoConfig $donmoConfig;
    private DonationRepositoryInterface $donationRepository;
    private DonationResourceModel $donationResource;

    private CartRepositoryInterface $cartRepository;
    private SerializerInterface $serializer;
    private MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId;

    public function __construct(
        Logger $logger,
        DonationInterfaceFactory        $donationFactory,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId,
        DonmoConfig                     $donmoConfig,
        DonationRepositoryInterface     $donationRepository,
        DonationResourceModel $donationResource,
        CartRepositoryInterface $cartRepository,
        SerializerInterface $serializer,
        MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
    ) {
        $this->logger = $logger;
        $this->donationFactory = $donationFactory;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
        $this->donmoConfig = $donmoConfig;
        $this->donationRepository = $donationRepository;
        $this->donationResource = $donationResource;
        $this->cartRepository = $cartRepository;
        $this->serializer = $serializer;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
    }

    /**
     * @inheritdoc
     */
    public function createDonation(Order $order): void
    {
        $donationAmount = $order->getDonmodonation();

        $orderId = $order->getId();

        $quoteId = $order->getQuoteId();
        $maskedId = $this->quoteIdToMaskedQuoteId->execute($quoteId);
        $currentMode = $this->donmoConfig->getCurrentMode();
        $currency = $order->getOrderCurrency()->getCurrencyCode();

        if ($donationAmount) {
            $donation = $this->donationFactory->create();
            $donation
                ->setOrderId($orderId)
                ->setMaskedQuoteId($maskedId)
                ->setDonationAmount($donationAmount)
                ->setCurrency($currency)
                ->setMode($currentMode)
                ->setStatus(self::STATUS_PENDING);

            $this->donationRepository->save($donation);
        }
    }

    /**
     * @inheritdoc
     */
    public function getByOrder(Order $order): DonationInterface
    {
        $orderId = $order->getId();
        $entity = $this->donationFactory->create();

        $this->donationResource->load($entity, $orderId, DonationInterface::ORDER_ID);

        if (!$entity->getDonationId()) {
            throw new NoSuchEntityException(__("The requested donation does not exist."));
        }

        return $entity;
    }

    // REST API Services

    public function addDonationToCart(string $cartId, float $donationAmount): string
    {
        try {
            $quoteId = $this->maskedQuoteIdToQuoteId->execute($cartId);
            $quote = $this->cartRepository->get($quoteId);

            if ($donationAmount > 0) {
                $quote->setDonmodonation($donationAmount)->collectTotals();
                $this->cartRepository->save($quote);

                return $this->serializer->serialize(['message' => 'Success']);
            } else {
                return $this->serializer->serialize(['message' => 'Invalid donation']);
            }
        } catch (NoSuchEntityException $e) {
            return $this->serializer->serialize(["message" => "The quote could not be loaded"]);
        } catch (\Exception $e) {
            return $this->serializer->serialize(["message" => "An error has occurred: " . $e->getMessage()]);
        }
    }

    /**
     * Remove donation from quote

     * @param string $cartId
     * @return string
     */
    public function removeDonationFromCart(string $cartId): string
    {
        try {
            $quoteId = $this->maskedQuoteIdToQuoteId->execute($cartId);
            $quote = $this->cartRepository->get($quoteId);

            $quote->setDonmodonation(0)->collectTotals();
            $this->cartRepository->save($quote);

            return $this->serializer->serialize(['message' => 'Success']);
        } catch (NoSuchEntityException $e) {
            return $this->serializer->serialize(["message" => "The quote could not be loaded"]);
        } catch (\Exception $e) {
            return $this->serializer->serialize(["message" => "An error has occurred: " . $e->getMessage()]);
        }
    }
}
