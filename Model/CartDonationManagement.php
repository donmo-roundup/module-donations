<?php

namespace Donmo\Roundup\Model;

use Donmo\Roundup\Api\CartDonationManagementInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class CartDonationManagement implements CartDonationManagementInterface
{
    public function __construct(
        CartRepositoryInterface $cartRepository,
        SerializerInterface $serializer
    ) {
        $this->cartRepository = $cartRepository;
        $this->serializer = $serializer;
    }

    /**
    @inheritdoc
     */
    public function addDonationToCart(int $cartId, float $donationAmount): string
    {
        try {
            $quote = $this->cartRepository->get($cartId);

            if ($donationAmount > 0) {
                $quote->setDonmodonation($donationAmount)->collectTotals();
                $this->cartRepository->save($quote);

                return $this->serializer->serialize(['message' => 'Success']);
            } else {
                return $this->serializer->serialize(['message' => 'Invalid donation']);
            }
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(__('The quote could not be loaded'));
        } catch (\Exception $e) {
            throw new \Exception('Some error has occurred');
        }
    }

    /**
     * @inheritdoc
     */
    public function removeDonationFromCart(int $cartId): string
    {
        try {
            $quote = $this->cartRepository->get($cartId);

            $quote->setDonmodonation(0)->collectTotals();
            $this->cartRepository->save($quote);

            return $this->serializer->serialize(['message' => 'Success']);
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(__('The quote could not be loaded'));
        } catch (\Exception $e) {
            throw new \Exception('Some error has occurred');
        }
    }
}
