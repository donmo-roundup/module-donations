<?php

namespace Donmo\Roundup\Model\Donmo;

use Magento\Framework\Model\AbstractModel;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation as DonationResource;
class Donation extends AbstractModel
{
    const STATUS_PENDING = "PENDING";
    const STATUS_CONFIRMED = "CONFIRMED";
    const STATUS_DELETED = "DELETED";
    public function _construct()
    {
        $this->_init(DonationResource::class);
    }

}
