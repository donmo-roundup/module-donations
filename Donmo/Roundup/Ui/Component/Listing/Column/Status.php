<?php

namespace Donmo\Roundup\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;
use Donmo\Roundup\Model\Donmo\Donation as DonationModel;
class Status implements OptionSourceInterface
{

    protected $options;

    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }
        $this->options[DonationModel::STATUS_CONFIRMED]['label'] = DonationModel::STATUS_CONFIRMED;
        $this->options[DonationModel::STATUS_CONFIRMED]['value'] = DonationModel::STATUS_CONFIRMED;

        $this->options[DonationModel::STATUS_PENDING]['label'] = DonationModel::STATUS_PENDING;
        $this->options[DonationModel::STATUS_PENDING]['value'] = DonationModel::STATUS_PENDING;

        $this->options[DonationModel::STATUS_DELETED]['label'] = DonationModel::STATUS_DELETED;
        $this->options[DonationModel::STATUS_DELETED]['value'] = DonationModel::STATUS_DELETED;

        return $this->options;
    }
}

