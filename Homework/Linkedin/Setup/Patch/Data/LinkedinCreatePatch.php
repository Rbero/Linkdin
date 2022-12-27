<?php

namespace Homework\Linkedin\Setup\Patch\Data;

use Exception;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\ResourceModel\Attribute as AttributeResource;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Psr\Log\LoggerInterface;


class LinkedinCreatePatch implements DataPatchInterface, PatchRevertableInterface
{
    const Linkedin_Customer = "Linkedin";

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory     $customerSetupFactory,
        AttributeResource        $attributeResource,
        LoggerInterface          $logger
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetup = $customerSetupFactory->create(['setup' => $moduleDataSetup]);
        $this->attributeResource = $attributeResource;
        $this->logger = $logger;
    }


    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        try {
            $this->moduleDataSetup->getConnection()->startSetup();

            $this->customerSetup->addAttribute(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                self::Linkedin_Customer,
                [
                    'label' => 'Linkedin',
                    'required' => 1,
                    'unique' => 1,
                    'position' => 200,
                    'system' => 0,
                    'user_defined' => 1,
                    'is_used_in_grid' => 1,
                    'is_visible_in_grid' => 1,
                    'is_filterable_in_grid' => 1,
                    'is_searchable_in_grid' => 1,
                    'backend' => (\Homework\Linkedin\Model\Customer\Attribute\LinkedinBackend::class)
                ]
            );

            $this->customerSetup->addAttributeToSet(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
                null,
                self::Linkedin_Customer
            );


            $attribute = $this->customerSetup->getEavConfig()
                ->getAttribute(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, self::Linkedin_Customer);


            $attribute->setData('used_in_forms', [
                'adminhtml_checkout',
                'adminhtml_customer',
                'customer_account_create',
                'customer_account_edit',
                'checkout_register'
            ]);

            $attribute->setData('validate_rules', [
                'input_validation' => 1,
                'min_text_length' => 1,
                'max_text_length' => 250
            ]);

            $this->attributeResource->save($attribute);
        } catch (Exception $exception) {
            $this->logger->err($exception->getMessage());
        }
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->customerSetup->removeAttribute(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            self::Linkedin_Customer
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}

