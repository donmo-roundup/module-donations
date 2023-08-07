<?php

namespace Donmo\Roundup\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Report extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('donmo_donation', 'entity_id');
    }
}
