<?php

namespace Donmo\Roundup\Model\Donmo\ResourceModel\Donation;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;
use Donmo\Roundup\Model\Donmo\Donation;
class Collection extends AbstractCollection
{

    public function _construct()
    {
        $this->_init(Donation::class, DonationResource::class);
    }

}
