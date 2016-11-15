<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160925093000 extends AbstractMigration
{

    public function up(Schema $schema)
    {
        $this->createPluginTable($schema);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('plg_portfolio');
        $schema->dropTable('plg_portfolio_image');
    }

    protected function createPluginTable(Schema $schema)
    {
        $table = $schema->createTable("plg_portfolio");
        $table->addColumn('id', 'integer', array('autoincrement' => true));
        $table->addColumn('order_id', 'integer', array('notnull' => false));
        $table->addColumn('customer_id', 'integer', array('notnull' => false));
        $table->addColumn('name', 'text', array('notnull' => false));
        $table->addColumn('product_id', 'integer', array('notnull' => false));
        $table->addColumn('type', 'integer', array('notnull' => false));
        $table->addColumn('img', 'text', array('notnull' => false));
        $table->addColumn('page_no', 'integer', array('notnull' => false));
        $table->addColumn('publish', 'integer', array('notnull' => false));
        $table->addColumn('comment', 'text', array('notnull' => false));
        $table->addColumn('rank', 'integer', array('notnull' => true));
        $table->setPrimaryKey(array('id'));
        $table->addColumn('create_date', 'datetime', array(
            'notnull' => true,
            'unsigned' => false,
        ));

        $table->addColumn('update_date', 'datetime', array(
            'notnull' => true,
            'unsigned' => false,
        ));
        $table->addColumn('del_flg', 'integer', array('notnull' => false));

        $table = $schema->createTable("plg_portfolio_image");
        $table->addColumn('image_id', 'integer', array('autoincrement' => true));
        $table->addColumn('portfolio_id', 'integer', array('notnull' => false));
        $table->addColumn('file_name', 'text', array('notnull' => false));
        $table->addColumn('rank', 'integer', array('notnull' => true));
        $table->addColumn('create_date', 'datetime', array(
            'notnull' => true,
            'unsigned' => false,
        ));
    }

}
