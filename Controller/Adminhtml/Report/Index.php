<?php

namespace Donmo\Roundup\Controller\Adminhtml\Report;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\Controller\ResultInterface;

class Index extends Action implements HttpGetActionInterface
{

    public function execute(): ResultInterface
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Donmo_Roundup::donmo_report');
        $resultPage->getConfig()->getTitle()->prepend(__('Donmo Report'));
        return $resultPage;
    }
}
