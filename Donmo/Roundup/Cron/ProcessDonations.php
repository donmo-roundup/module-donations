<?php

namespace Donmo\Roundup\Cron;
use Donmo\Roundup\lib\ApiService;

use Donmo\Roundup\Model\Donmo\Donation as DonationModel;

use \Magento\Framework\App\ResourceConnection;
use \Magento\Framework\Model\ResourceModel\IteratorFactory;

use Donmo\Roundup\Model\Config as DonmoConfig;

use Donmo\Roundup\Logger\Logger;
class ProcessDonations
{
    private $connection;
    private DonmoConfig $donmoConfig;
    private Logger $logger;
    private IteratorFactory $iteratorFactory;
    private array $payload;
    private ApiService $apiService;

    public function __construct(
        DonmoConfig $donmoConfig,
        Logger $logger,
        ResourceConnection $resource,
        IteratorFactory $iteratorFactory,
        ApiService $apiService
    )
    {
        $this->donmoConfig = $donmoConfig;
        $this->logger = $logger;
        $this->connection = $resource->getConnection();
        $this->iteratorFactory = $iteratorFactory;
        $this->payload = array();
        $this->apiService = $apiService;
    }

    public function execute() {
        try {
            $currentMode = $this->donmoConfig->getCurrentMode();

            $query = $this->connection->select()->from('donmo_donation')
                ->where('status = ?', DonationModel::STATUS_CONFIRMED)
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
                $status = $this->apiService->createAndConfirmDonations($currentMode, $this->payload);

                if ($status == 200) {
                    return $this->connection->update('donmo_donation',
                        ['status' => DonationModel::STATUS_SENT], ['status = ?' => DonationModel::STATUS_CONFIRMED]);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error("Recording donations error (Magento DB): \n" . $e);
        }

    }

}
