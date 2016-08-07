<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 07.08.16
 * Time: 15:35
 */

namespace Dopamedia\StateMachine\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @inheritDoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'state_machine_process'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('state_machine_process')
        )->addColumn(
            'process_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Process ID'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Process Name'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Update Time'
        )->addIndex(
            $installer->getIdxName(
                'state_machine_process',
                ['name'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['name'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->setComment(
            'StateMachine Process Table'
        );

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'state_machine_item_state'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('state_machine_item_state')
        )->addColumn(
            'item_state_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Item State ID'
        )->addColumn(
            'process_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Process ID'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'State Name'
        )->addIndex(
            $installer->getIdxName(
                'state_machine_item_state',
                ['process_id', 'name'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['process_id', 'name'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
            $installer->getFkName('state_machine_item_state', 'process_id', 'state_machine_process', 'process_id'),
            'process_id',
            $installer->getTable('state_machine_process'),
            'process_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'StateMachine Item State Table'
        );

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'state_machine_item_state_history'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('state_machine_item_state_history')
        )->addColumn(
            'item_state_history_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Item State ID'
        )->addColumn(
            'item_state_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Item State ID'
        )->addColumn(
            'process_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Process ID'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'State Name'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addForeignKey(
            $installer->getFkName('state_machine_item_state_history', 'item_state_id', 'state_machine_item_state', 'item_state_id'),
            'item_state_id',
            $installer->getTable('state_machine_item_state'),
            'item_state_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'StateMachine Item State History Table'
        );

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'state_machine_transition_log'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('state_machine_transition_log')
        )->addColumn(
            'transition_log_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Transition Log ID'
        )->addColumn(
            'process_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Process ID'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addForeignKey(
            $installer->getFkName('state_machine_transition_log', 'process_id', 'state_machine_process', 'process_id'),
            'process_id',
            $installer->getTable('state_machine_process'),
            'process_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'StateMachine Transition Log Table'
        );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}