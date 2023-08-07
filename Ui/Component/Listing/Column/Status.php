<?php

namespace Donmo\Roundup\Ui\Component\Listing\Column;

use Donmo\Roundup\Api\DonationManagementInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    protected array $options;

    public function toOptionArray(): array
    {
        $this->options[DonationManagementInterface::STATUS_PENDING]['label'] = DonationManagementInterface::STATUS_PENDING;
        $this->options[DonationManagementInterface::STATUS_PENDING]['value'] = DonationManagementInterface::STATUS_PENDING;

        $this->options[DonationManagementInterface::STATUS_CONFIRMED]['label'] = DonationManagementInterface::STATUS_CONFIRMED;
        $this->options[DonationManagementInterface::STATUS_CONFIRMED]['value'] = DonationManagementInterface::STATUS_CONFIRMED;

        $this->options[DonationManagementInterface::STATUS_CANCELED]['label'] = DonationManagementInterface::STATUS_CANCELED;
        $this->options[DonationManagementInterface::STATUS_CANCELED]['value'] = DonationManagementInterface::STATUS_CANCELED;

        $this->options[DonationManagementInterface::STATUS_REFUNDED]['label'] = DonationManagementInterface::STATUS_REFUNDED;
        $this->options[DonationManagementInterface::STATUS_REFUNDED]['value'] = DonationManagementInterface::STATUS_REFUNDED;

        $this->options[DonationManagementInterface::STATUS_SENT]['label'] = DonationManagementInterface::STATUS_SENT;
        $this->options[DonationManagementInterface::STATUS_SENT]['value'] = DonationManagementInterface::STATUS_SENT;

        return $this->options;
    }
}
