<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$connection = $installer->getConnection();

$tableName = 'real_de_orders';

$table = $connection
	->newTable($tableName)
	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, ['primary' => true, 'auto_increment' => true])
	->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, ['default' => 0])
	->addColumn('real_de_order_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50)
	->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT)
	->addColumn('processed', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, ['default' => 0])
	->addColumn('magento_order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, ['default' => 0])
	->addColumn('error', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, ['default' => 0])
	->addColumn('error_message', Varien_Db_Ddl_Table::TYPE_TEXT)
	->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME)
	->addIndex('store_id', ['store_id'])
	->addIndex('real_de_order_id', ['real_de_order_id'])
	->addIndex('processed', ['processed'])
	->addIndex('error', ['error']);

$connection->dropTable($tableName);
$connection->createTable($table);


$setup = new Mage_Sales_Model_Resource_Setup('core_setup');
$setup->addAttribute('quote', 'real_de_order_id', ['type' => 'varchar']);
$setup->addAttribute('quote_item', 'real_de_order_unit_id', ['type' => 'varchar']);
$setup->addAttribute('order', 'real_de_order_id', ['type' => 'varchar']);
$setup->addAttribute('order_item', 'real_de_order_unit_id', ['type' => 'varchar']);

$installer->endSetup();
