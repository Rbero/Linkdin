<?php

namespace Homework\Linkedin\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Block\Account\Dashboard;

class LinkedinBlock extends Template
{
    protected $scopeConfig;

    protected $Dashboard;

    const XML_PATH_Linkedin = 'customer/create_account/Linkedin';

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        Dashboard $Dashboard,
        array $data = []
    )
    {
        $this->Dashboard=$Dashboard;
        $this->scopeConfig=$scopeConfig;
        parent::__construct($context, $data);
    }

    public function getReciveLinkedin()
    {
        $scopeConfig = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;

        return $this->scopeConfig->getValue(self::XML_PATH_Linkedin, $scopeConfig);
    }

    public function getLinkedin()
    {
        if ($_SERVER['REQUEST_URI'] === '/customer/account/edit/') {
            return $this->Dashboard->getCustomer()->getCustomAttribute('Linkedin')->getValue();
        }

        return null;
    }
}
