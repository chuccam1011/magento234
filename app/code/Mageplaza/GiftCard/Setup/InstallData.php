<?php

namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $data = [
            'code'  => "ASDDDAS444",
            'balance' =>12,
            'amount_used'=>0,
            'created_from'=>'admin'

        ];

        $table = $setup->getTable('mageplaza_giftcard_code');
        $setup->getConnection()->insert($table, $data);

        $setup->endSetup();
    }
}
