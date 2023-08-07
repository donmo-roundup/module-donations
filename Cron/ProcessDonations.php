<?php

namespace Donmo\Roundup\Cron;

use Donmo\Roundup\Logger\Logger;
use Donmo\Roundup\Api\DonationManagementInterface;
use Donmo\Roundup\Api\DonationRepositoryInterface;
use Donmo\Roundup\lib\ApiService;
use Donmo\Roundup\Model\Config as DonmoConfig;
use Donmo\Roundup\Api\Data\DonationInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;

class ProcessDonations
{
    private Logger $logger;
    private DonmoConfig $donmoConfig;
    private ApiService $apiService;
    private DonationRepositoryInterface $donationRepository;
    private FilterBuilder $filterBuilder;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        Logger $logger,
        DonmoConfig $donmoConfig,
        ApiService $apiService,
        DonationRepositoryInterface $donationRepository,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->logger = $logger;
        $this->donmoConfig = $donmoConfig;
        $this->apiService = $apiService;
        $this->donationRepository = $donationRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute(): void
    {
        try {
            $currentMode = $this->donmoConfig->getCurrentMode();
            $statusFilter = $this->filterBuilder->setField(DonationInterface::STATUS)->setValue(DonationManagementInterface::STATUS_CONFIRMED)->setConditionType('eq')->create();
            $modeFilter = $this->filterBuilder->setField(DonationInterface::MODE)->setValue($currentMode)->setConditionType('eq')->create();

            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilters([$statusFilter])
                ->addFilters([$modeFilter])
                ->create();

            $donations = $this->donationRepository->getList($searchCriteria)->getItems();

            if (count($donations)) {
                $status = $this->apiService->createAndConfirmDonations($currentMode, $donations);

                if ($status == 200) {
                    foreach ($donations as $donation) {
                        $donation->setStatus(DonationManagementInterface::STATUS_SENT);
                        $this->donationRepository->save($donation);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error("ProcessDonations Cron Error: \n" . $e);
        }
    }
}
