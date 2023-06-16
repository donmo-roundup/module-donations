<?php

namespace Donmo\Roundup\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Donmo\Roundup\Model\Config as DonmoConfig;

use Magento\Framework\Intl\DateTimeFactory;


use Donmo\Roundup\Model\Donmo\DonationFactory;
use Donmo\Roundup\Model\Donmo\Donation as DonationModel;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;

use Psr\Log\LoggerInterface;
class ConfirmDonationOnOrderComplete implements ObserverInterface
{
    private DonmoConfig $donmoConfig;
    private DonationFactory $donationFactory;
    private DonationResource $donationResource;

    private DateTimeFactory $dateTimeFactory;

    private LoggerInterface $logger;

    public function __construct(
        DonationFactory  $donationFactory,
        DonationResource $donationResource,
        DateTimeFactory $dateTimeFactory,
        LoggerInterface $logger,
        DonmoConfig $config
    )
    {
        $this->donationFactory = $donationFactory;
        $this->donationResource = $donationResource;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->logger = $logger;
        $this->donmoConfig = $config;
    }


    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();

        if ($order->getDonmodonation() > 0) {
            $orderId = $order->getId();
            $currentMode = $this->donmoConfig->getCurrentMode();
            $createdAt = $this->dateTimeFactory->create(
                $order->getCreatedAt(),
                new \DateTimeZone('UTC')
            );

            $donationModel = $this->donationFactory->create();

            $donationModel
                ->setOrderId($orderId)
                ->setDonationAmount($order->getDonmodonation())
                ->setCreatedAt($createdAt)
                ->setStatus(DonationModel::STATUS_PENDING)
                ->setMode($currentMode);
        }

        try {
            $this->donationResource->save($donationModel);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }
    }
}
