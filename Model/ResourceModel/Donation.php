<?php

namespace Donmo\Roundup\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Donation extends AbstractDb
{
    private const TABLE_NAME = 'donmo_donation';
    private const PRIMARY_KEY = 'donation_id';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}
