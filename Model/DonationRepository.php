<?php

namespace Donmo\Roundup\Model;

use Donmo\Roundup\Api\Data\DonationSearchResultsInterface;
use Donmo\Roundup\Model\ResourceModel\Donation as DonationResourceModel;
use Donmo\Roundup\Api\Data\DonationInterfaceFactory;
use Donmo\Roundup\Api\Data\DonationSearchResultsInterfaceFactory;
use Donmo\Roundup\Api\Data\DonationInterface;
use Donmo\Roundup\Api\DonationRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

use Donmo\Roundup\Model\ResourceModel\Donation\CollectionFactory as DonationCollectionFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

class DonationRepository implements DonationRepositoryInterface
{
    private DonationResourceModel $donationResource;
    private DonationInterfaceFactory $donationFactory;
    private DonationSearchResultsInterfaceFactory $searchResultsFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private DonationCollectionFactory $donationCollectionFactory;

    public function __construct(
        DonationResourceModel $donationResource,
        DonationInterfaceFactory $donationFactory,
        DonationSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        DonationCollectionFactory $donationCollectionFactory
    ) {
        $this->donationResource = $donationResource;
        $this->donationFactory = $donationFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->donationCollectionFactory = $donationCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(DonationInterface $donation): DonationInterface
    {
        try {
            $this->donationResource->save($donation);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save the donation.'), $e);
        }
        return $donation;
    }

    /**
     * @inheritdoc
     */
    public function get(int $id): DonationInterface
    {
        if (!$id) {
            throw new InputException(__('A donation ID is required.'));
        }
        $entity = $this->donationFactory->create();
        $this->donationResource->load($entity, $id);
        if (!$entity->getDonationId()) {
            throw new NoSuchEntityException(__("The requested donation does not exist."));
        }
        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): DonationSearchResultsInterface
    {
        $collection = $this->donationCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
    /**
     * @inheritdoc
     */
    public function delete(DonationInterface $donation): bool
    {
        try {
            $this->donationResource->delete($donation);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete the donation.'), $e);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($id): bool
    {
        $model = $this->get($id);
        $this->delete($model);
        return true;
    }
}
