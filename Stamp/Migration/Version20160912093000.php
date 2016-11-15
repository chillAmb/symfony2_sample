<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160912093000 extends AbstractMigration
{

    public function up(Schema $schema)
    {
        $this->createPluginTable($schema);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('plg_stamp');
    }

    protected function createPluginTable(Schema $schema)
    {
        $table = $schema->createTable("plg_stamp");
        $table->addColumn('id', 'integer', array('autoincrement' => true));
        $table->addColumn('name', 'text', array('notnull' => false));
        $table->addColumn('type', 'integer', array('notnull' => false));
        $table->addColumn('img', 'text', array('notnull' => false));
        $table->addColumn('publish', 'integer', array('notnull' => false));
        $table->addColumn('rank', 'integer', array('notnull' => false));
        $table->setPrimaryKey(array('id'));
        $table->addColumn('create_date', 'datetime', array(
            'notnull' => true,
            'unsigned' => false,
        ));

        $table->addColumn('update_date', 'datetime', array(
            'notnull' => true,
            'unsigned' => false,
        ));
    }

}
