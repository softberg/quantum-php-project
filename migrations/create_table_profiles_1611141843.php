<?php

use Quantum\Migration\QtMigration;
use Quantum\Migration\Schema;

class CreateTableProfiles1611141843 extends QtMigration
{

    public function up(Schema $schema)
    {
        $schema->createTable('profiles', [
            'id' => ['type' => 'int', 'limit' => 11, 'autoincrement' => true],
            'employee_id' => ['type' => 'int', 'limit' => 11, 'key' => 'index'],
            'bio' => ['type' => 'text'],
            'married' => ['type' => 'enum', 'limit' => '\'yes\'', '\'no\'', 'default' => 'no'],
            'income' => ['type' => 'decimal', 'limit' => '5,2', 'nullable' => true],
        ]);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('profiles');
    }

}
