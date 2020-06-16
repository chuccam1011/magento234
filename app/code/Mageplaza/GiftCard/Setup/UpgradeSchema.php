<?php


namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '2.1.0', '<')) {
            if (!$installer->tableExists('giftcard_history')) {
                try {
                    $table = $installer->getConnection()->newTable(
                        $installer->getTable('giftcard_history')
                    )
                        ->addColumn(
                            'history_id',
                            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            null,
                            [
                                'identity' => true,
                                'nullable' => false,
                                'primary' => true,
                                'unsigned' => true,
                            ],
                            'GiftCard use history'
                        )
                        ->addColumn(
                            'giftcard_id',
                            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            11,
                            ['nullable => false', 'unsigned' => true],
                            'giftcard_id'
                        )
                        ->addForeignKey(
                            $installer->getFkName('giftcard_history', 'giftcard_id', 'mageplaza_giftcard_code', 'giftcard_id'),
                            'giftcard_id',
                            $installer->getTable('mageplaza_giftcard_code'),
                            'giftcard_id',
                            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                        )
                        ->addColumn(
                            'customer_id',
                            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            11,
                            ['nullable => false', 'unsigned' => true],
                            'customer_id'
                        )
                        ->addForeignKey(
                            $installer->getFkName('giftcard_history', 'customer_id', 'customer_entity', 'entity_id'),
                            'customer_id',
                            $installer->getTable('customer_entity'),
                            'entity_id',
                            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                        )
                        ->addColumn(
                            'amount',
                            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                            12.4,
                            [],
                            'amount  has be change'
                        )
                        ->addColumn(
                            'action',
                            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            255,
                            [],
                            'create/redeem/Used for order'
                        )->addColumn(
                            'action_time',
                            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                            null,
                            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                            'Time Action')
                        ->setComment('History Use GifrCard');

                } catch (\Zend_Db_Exception $e) {
                    echo $e;
                }
                try {
                    $installer->getConnection()->createTable($table);
                } catch (\Zend_Db_Exception $e) {
                    echo $e;
                }

            }
            if (!$installer->tableExists('giftcard_customer_balance')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('giftcard_customer_balance')
                )
                    ->addColumn(
                        'customer_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        11,
                        ['nullable => false', 'unsigned' => true],
                        'customer_id'
                    )
                    ->addForeignKey(
                        $installer->getFkName('giftcard_customer_balance', 'customer_id', 'customer_entity', 'entity_id'),
                        'customer_id',
                        $installer->getTable('customer_entity'),
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addColumn(
                        'balance',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        12.4,
                        [],
                        'count redeem'
                    )
                    ->setComment('giftcard_customer_balance ');
                try {


                    $installer->getConnection()->createTable($table);
                } catch (\Zend_Db_Exception $e) {
                    echo $e;

                }

            }
            if ($installer->tableExists('quote')) {
                $installer->getConnection()->addColumn(
                    $installer->getTable( 'quote' ),
                    'giftcard_code',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length' => '255',
                        'comment' => 'giftcard_code',
                        'after' => 'is_persistent'
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable( 'quote' ),
                    'giftcard_base_discount',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        'nullable' => true,
                        'length' => '12,4',
                        'comment' => 'Amount has been discount from apply GiftCard in Cart',
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable( 'quote' ),
                    'giftcard_discount',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        'nullable' => true,
                        'length' => '12,4',
                        'comment' => 'Amount has been discount from apply GiftCard in Cart',
                    ]
                );

            }
        }

        $installer->endSetup();
    }
}
