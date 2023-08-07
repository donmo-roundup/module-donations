<?php
namespace Donmo\Roundup\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DonationSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get donations list.
     *
     * @return DonationInterface[]
     */
    public function getItems();

    /**
     * Set donations list.
     *
     * @param DonationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
