<?php

namespace Donmo\Roundup\Cron;
use Magento\Framework\HTTP\Client\Curl;

use Donmo\Roundup\Model\Donmo\ResourceModel\Donation\CollectionFactory;
use Donmo\Roundup\Model\Donmo\DonationFactory;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\Serialize\Serializer\Json;

use \Magento\Framework\App\ResourceConnection;
use \Magento\Framework\Model\ResourceModel\IteratorFactory;

use Donmo\Roundup\Model\Config as DonmoConfig;
use Donmo\Roundup\lib\Donmo as Donmo;

use Donmo\Roundup\Logger\Logger;
class ProcessDonations
{
    private $connection;
    private ResourceConnection $resource;

    private DonmoConfig $donmoConfig;
    private DonationResource $donationResource;
    private DonationFactory $donationFactory;
    private Logger $logger;
    private ScopeConfigInterface $scopeConfig;
    private Curl $curl;
    private Json $json;
    private CollectionFactory $collectionFactory;
    private IteratorFactory $iteratorFactory;

    private array $payload;

    public function __construct(
        DonmoConfig $donmoConfig,
        CollectionFactory $collectionFactory,
        DonationFactory  $donationFactory,
        DonationResource $donationResource,
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        Curl $curl,
        Json $json,
        ResourceConnection $resource,
        IteratorFactory $iteratorFactory
    )
    {
        $this->donmoConfig = $donmoConfig;
        $this->collectionFactory = $collectionFactory;
        $this->donationFactory = $donationFactory;
        $this->donationResource = $donationResource;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->curl = $curl;
        $this->json = $json;
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
        $this->iteratorFactory = $iteratorFactory;
        $this->payload = array();
    }

    public function execute() {
        $currentMode = $this->donmoConfig->getCurrentMode();

        $query = $this->connection->select()->from('donmo_donation')
            ->where('status = ?', 'PENDING')
            ->where('mode = ?', $currentMode);

        $iterator = $this->iteratorFactory->create();

         $iterator->walk((string) $query, [function (array $result) {
             $donation_data = [
                 'donationAmount' => (float) $result['row']['donation_amount'],
                 'createdAt' => $result['row']['created_at'],
                 'orderId' => $result['row']['masked_quote_id'],
             ];

             $this->payload[] = $donation_data;
        }], [], $this->connection);


        if (count($this->payload)) {

            $url = Donmo::$apiBase . '/donations/confirm';

            $sk = $this->donmoConfig->getSecretKey($currentMode);

            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("sk", $sk);
            $this->curl->post($url, $this->json->serialize(['donations' => $this->payload]));

            $result = $this->curl->getBody();

            $status = $this->json->unserialize($result)['status'];

            if ($status == 200) {
                try {
                    return $this->connection->update('donmo_donation',
                    ['status' => 'CONFIRMED'], ['status = ?' => 'PENDING']);
                } catch (\Exception $e) {
                    $this->logger->error("Recording donations error (Magento DB): \n" . $e);
                }
            } else {
                $this->logger->error("Recording donations error (API): \n" . $result);
            }
        }
    }

}
