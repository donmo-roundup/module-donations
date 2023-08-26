<?php

namespace Donmo\Roundup\lib;

use Donmo\Roundup\Api\Data\DonationInterface;
use Donmo\Roundup\lib\Donmo as Donmo;
use Donmo\Roundup\Logger\Logger;
use Donmo\Roundup\Model\Config as DonmoConfig;

class ApiService
{
    private Logger $logger;
    private DonmoConfig $donmoConfig;

    public function __construct(
        Logger $logger,
        DonmoConfig $donmoConfig
    ) {
        $this->logger = $logger;
        $this->donmoConfig = $donmoConfig;
    }

    /**
     * @param DonationInterface[] $donations
     * @return array
     */
    private function generatePayload(array $donations): array
    {
        $payload = [];
        foreach ($donations as $donation) {
            $payload[] = [
                'donationAmount' => $donation->getDonationAmount(),
                'createdAt' => $donation->getCreatedAt(),
                'orderId' => $donation->getMaskedQuoteId()
            ];
        }
        return $payload;
    }

    /**
     * @param $mode
     * @param DonationInterface[] $donations
     * @return int
     */
    public function createAndConfirmDonations($mode, array $donations): int
    {
        $sk = $this->donmoConfig->getSecretKey($mode);

        $url = Donmo::$apiBase . '/donations/confirm';

        $ch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            "sk: $sk"
        );

        $payload = $this->generatePayload($donations);
        $body = json_encode(['donations' => $payload]);

        curl_setopt($ch, CURLOPT_URL, $url); // URL to request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $response = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status == 200) {
            $this->logger->info("Donmo CreateAndConfirmDonations API Request Successful: \n" . $response);
        } else {
            $this->logger->error("Unsuccessful Donmo CreateAndConfirmDonations API Request: \n" . $response);
        }

        return $status;
    }
    public function deleteDonation($donationMode, $id): int
    {
        $sk = $this->donmoConfig->getSecretKey($donationMode);

        $url = Donmo::$apiBase . "/donations/{$id}";

        $ch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            "sk: $sk"
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status == 200) {
            $this->logger->info("Donmo DeleteDonation API Request Successful: \n" . $response);
        } else {
            $this->logger->error("Unsuccessful Delete Donation API request: \n" . $response);
        }

            return $status;
    }
}
