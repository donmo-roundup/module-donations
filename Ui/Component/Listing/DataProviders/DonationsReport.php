<?php

namespace Donmo\Roundup\Ui\Component\Listing\DataProviders;

use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Donmo\Roundup\Model\Donmo\ResourceModel\Donation\CollectionFactory;

class DonationsReport extends ModifierPoolDataProvider{

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
