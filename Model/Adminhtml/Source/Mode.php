<?php

namespace Donmo\Roundup\Model\Adminhtml\Source;

class Mode
{
    const TEST = 'test';
    const LIVE = 'live';

    public function toOptionArray(): array
    {
        return [
            [
                'value' => Mode::LIVE,
                'label' => __('Live')
            ],
            [
                'value' => Mode::TEST,
                'label' => __('Test')
            ]
        ];
    }
}
