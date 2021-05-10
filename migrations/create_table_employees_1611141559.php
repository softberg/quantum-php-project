<?php

use Quantum\Migration\QtMigration;
use Quantum\Migration\Schema;

class CreateTableEmployees1611141559 extends QtMigration
{

    public function up(Schema $schema)
    {
        $schema->createTable('employees', [
            'id' => ['type' => 'int', 'limit' => 11, 'autoincrement' => true],
            'email' => ['type' => 'varchar', 'limit' => 50, 'key' => 'unique'],
            'pasword' => ['type' => 'varchar', 'limit' => 32]
        ]);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('employees');
    }

}
