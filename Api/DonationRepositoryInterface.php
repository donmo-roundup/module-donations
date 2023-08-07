<?php
namespace Donmo\Roundup\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Donmo\Roundup\Api\Data\DonationInterface;
use Donmo\Roundup\Api\Data\DonationSearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

interface DonationRepositoryInterface
{
    /**
     * Save donation.
     *
     * @param DonationInterface $donation
     * @return DonationInterface
     * @throws CouldNotSaveException
     */
    public function save(DonationInterface $donation): DonationInterface;

    /**
     * Retrieve donation by ID.
     *
     * @param int $id
     * @throws NoSuchEntityException
     * @throws InputException
     * @return DonationInterface
     */
    public function get(int $id): DonationInterface;

    /**
     * Retrieve donations matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return DonationSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): DonationSearchResultsInterface;

    /**
     * Delete donation.
     *
     * @param DonationInterface $donation
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(DonationInterface $donation): bool;

    /**
     * Delete donation by id
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException If ID is sent but the donation does not exist
     * @throws CouldNotDeleteException If there is a problem with the input
     */
    public function deleteById($id): bool;
}
