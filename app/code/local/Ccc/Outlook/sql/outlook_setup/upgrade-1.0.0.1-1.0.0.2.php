<?php
$installer = $this;
$installer->startSetup();

// Create outlook_email table
$tableNameEmail = $installer->getTable('outlook/email');
if ($installer->getConnection()->isTableExists($tableNameEmail) !== true) {
    $tableEmail = $installer->getConnection()
        ->newTable($tableNameEmail)
        ->addColumn('email_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
            'unsigned' => true,
        ), 'Email ID')
        ->addColumn('configuration_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
            'unsigned' => true,
        ), 'Configuration ID')
        ->addColumn('subject', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'Subject')
        ->addColumn('sender', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'Sender')
        ->addColumn('received_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
        ), 'Received At')
        ->addColumn('body', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable' => true,
        ), 'Body')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
        ), 'Created At')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
        ), 'Updated At')
        ->addForeignKey(
            $installer->getFkName('ccc_outlook_email', 'configuration_id', 'ccc_outlook_configuration', 'configuration_id'),
            'configuration_id',
            $installer->getTable('ccc_outlook_configuration'),
            'configuration_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Outlook Email Table');
    $installer->getConnection()->createTable($tableEmail);
}

// Create outlook_attachment table
$tableNameAttachment = $installer->getTable('outlook/attachment');
if ($installer->getConnection()->isTableExists($tableNameAttachment) !== true) {
    $tableAttachment = $installer->getConnection()
        ->newTable($tableNameAttachment)
        ->addColumn('attachment_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
            'unsigned' => true,
        ), 'Attachment ID')
        ->addColumn('email_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
            'unsigned' => true,
        ), 'Email ID')
        ->addColumn('file_path', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'File Path')
        ->addColumn('file_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
        ), 'File Name')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
        ), 'Created At')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable' => false,
            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE,
        ), 'Updated At')
        ->addForeignKey(
            $installer->getFkName('ccc_outlook_attachment', 'email_id', 'ccc_outlook_email', 'email_id'),
            'email_id',
            $installer->getTable('ccc_outlook_email'),
            'email_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Outlook Attachment Table');
    $installer->getConnection()->createTable($tableAttachment);
}


$tableNameEvent = $installer->getTable('outlook/event');
$tableEvent = $installer->getConnection()
    ->newTable($tableNameEvent)
    ->addColumn('event_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        'unsigned' => true,
    ), 'Event Id')
    ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ), 'Group Id')
    ->addColumn('field', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Field')
    ->addColumn('condition', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Condition')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Value')
    ->addColumn('event', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Event')
    ->setComment('Event Table');

$installer->getConnection()->createTable($tableEvent);

$installer->endSetup();
