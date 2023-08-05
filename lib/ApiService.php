<?php

namespace Donmo\Roundup\lib;

use Donmo\Roundup\lib\Donmo as Donmo;
use Donmo\Roundup\Logger\Logger;
use Donmo\Roundup\Model\Config as DonmoConfig;

use Magento\Framework\HTTP\ZendClient;
use Magento\Framework\HTTP\ZendClientFactory;
use Zend_Http_Client;

class ApiService
{
    private ZendClient $client;
    private Logger $logger;
    private DonmoConfig $donmoConfig;

    public function __construct(
        ZendClientFactory $httpClientFactory,
        Logger $logger,
        DonmoConfig $donmoConfig,
    ) {
        $this->client = $httpClientFactory->create();
        $this->logger = $logger;
        $this->donmoConfig = $donmoConfig;
    }


    public function createAndConfirmDonations($mode, $donations): int
    {
        $sk = $this->donmoConfig->getSecretKey($mode);

        $url = Donmo::$apiBase . '/donations/confirm';
        $this->client->setUri($url);
        $this->client->setMethod(Zend_Http_Client::POST);
        $this->client->setHeaders('sk', $sk);

        $json = json_encode(['donations' => $donations]);
        $result = $this->client->setRawData($json, 'application/json')->request();

        $status = $result->getStatus();
        $body = $result->getBody();

        if ($status == 200) {
            $this->logger->info("Donmo CreateAndConfirmDonations API Request Successful: \n" . $body);
        } else {
            $this->logger->error("Unsuccessful Donmo CreateAndConfirmDonations API Request: \n" . $body);
        }

        return $status;
    }
    public function deleteDonation($donationMode, $id): int
    {
            $sk = $this->donmoConfig->getSecretKey($donationMode);

            $url = Donmo::$apiBase . "/donations/{$id}";
            $this->client->setUri($url);
            $this->client->setMethod(Zend_Http_Client::DELETE);
            $this->client->setHeaders('sk', $sk);

            $result = $this->client->request();
            $status = $result->getStatus();
            $body = $result->getBody();
        if ($status == 200) {
            $this->logger->info("Donmo DeleteDonation API Request Successful: \n" . $body);
        } else {
            $this->logger->error("Unsuccessful Delete Donation API request: \n" . $body);
        }

            return $status;
    }
}
