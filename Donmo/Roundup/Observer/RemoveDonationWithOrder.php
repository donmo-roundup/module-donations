<?php

namespace Donmo\Roundup\Observer;

use Donmo\Roundup\Logger\Logger;
use Donmo\Roundup\Model\Config as DonmoConfig;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

use Zend_Http_Client;
use Magento\Framework\HTTP\ZendClient;
use Magento\Framework\HTTP\ZendClientFactory;

use Donmo\Roundup\Model\Donmo\DonationFactory;
use Donmo\Roundup\Model\Donmo\Donation as DonationModel;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;

use Donmo\Roundup\lib\Donmo as Donmo;

class RemoveDonationWithOrder implements ObserverInterface
{
    private ZendClient $client;
    private Logger $logger;
    private DonmoConfig $config;
    private DonationFactory $donationFactory;
    private DonationResource $donationResource;

    public function __construct(
        ZendClientFactory $httpClientFactory,
        Logger $logger,
        DonmoConfig $config,
        DonationFactory  $donationFactory,
        DonationResource $donationResource,
    )
    {
        $this->client = $httpClientFactory->create();
        $this->logger = $logger;
        $this->config = $config;
        $this->donationFactory = $donationFactory;
        $this->donationResource = $donationResource;
    }

    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();

        if($order->getDonmodonation() > 0) {
            $orderId = $order->getQuoteId();
            $donationModel = $this->donationFactory->create();
            $this->donationResource->load($donationModel, $orderId, 'order_id');
            $donationMode = $donationModel->getData('mode');

            // get secret key for donation mode (live vs test)
            $sk = $this->config->getSecretKey($donationMode);

            $url = Donmo::$apiBase . "/donations/{$orderId}";

            try{

                // Make request to Donmo API
                $this->client->setUri($url);
                $this->client->setMethod(Zend_Http_Client::DELETE);
                $this->client->setHeaders('sk', $sk);

                $status = $this->client->request()->getStatus();

                // Remove donation in DB after API success
                if ($status == 200) {
                    $donationModel->setData('status', DonationModel::STATUS_DELETED);
                    $this->donationResource->save($donationModel);
                }

            } catch (\Exception $e) {
                $this->logger->error("Donmo donation cancellation error: \n" . $e);
            }
        }

    }

}
