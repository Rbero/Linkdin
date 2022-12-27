<?php

namespace Homework\Linkedin\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class LinkedinValue implements ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('invisible')],
            ['value' => '1', 'label' => __('optional')],
            ['value' => '2', 'label' => __('required')]
        ];
    }
}
