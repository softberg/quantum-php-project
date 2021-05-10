<?php

use Quantum\Migration\QtMigration;
use Quantum\Migration\Schema;

class CreateTableJobs1611417907 extends QtMigration
{

    public function up(Schema $schema)
    {
        $schema->createTable('jobs', [
            'id' => ['type' => 'int', 'limit' => 11, 'autoincrement' => true],
            'title' => ['type' => 'varchar', 'limit' => 50],
            'description' => ['type' => 'text', 'nullable' => true],
            'salary' => ['type' => 'float', 'limit' => '6,2', 'default' => '0.00']
        ]);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('jobs');
    }

}
