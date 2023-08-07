<?php

namespace Donmo\Roundup\Model\ResourceModel\Donation;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Donmo\Roundup\Model\Donation;
use Donmo\Roundup\Model\ResourceModel\Donation as DonationResource;

class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    public function _construct()
    {
        $this->_init(
            Donation::class,
            DonationResource::class
        );
    }
}
