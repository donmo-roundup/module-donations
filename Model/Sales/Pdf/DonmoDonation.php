<?php

namespace Donmo\Roundup\Model\Sales\Pdf;

use Donmo\Roundup\Model\Config;
use Magento\Sales\Model\Order\Pdf\Total\DefaultTotal;
use Magento\Tax\Helper\Data;
use Magento\Tax\Model\Calculation;
use Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory;

class DonmoDonation extends DefaultTotal
{
    private Config $donmoConfig;

    public function __construct(
        Data $taxHelper,
        Calculation $taxCalculation,
        CollectionFactory $ordersFactory,
        Config $donmoConfig,
        array $data = []
    ) {
        parent::__construct($taxHelper, $taxCalculation, $ordersFactory, $data);

        $this->donmoConfig = $donmoConfig;
    }

    public function getTotalsForDisplay(): array
    {
        $amount = $this->getOrder()->getDonmodonation();

        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
        return [
            [
                'amount' => $this->getAmountPrefix() . $this->getOrder()->formatPriceTxt($amount),
                'label' => $this->donmoConfig->getDonationLabel() . ':',
                'font_size' => $fontSize,
            ]
        ];
    }
}
