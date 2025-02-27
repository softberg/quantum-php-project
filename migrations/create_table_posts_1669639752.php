<?php

use Quantum\Libraries\Database\Factories\TableFactory;
use Quantum\Migration\QtMigration;

class Create_table_posts_1669639752 extends QtMigration
{

    public function up(?TableFactory $tableFactory)
    {
        $table = $tableFactory->create('posts');
        $table->addColumn('id', 'integer')->primary()->autoIncrement();
        $table->addColumn('uuid', 'char', 36);
        $table->addColumn('user_id', 'integer')->index();
        $table->addColumn('title', 'varchar', 255);
        $table->addColumn('content', 'text');
        $table->addColumn('image', 'varchar', 255);
        $table->addColumn('updated_at', 'timestamp');
    }

    public function down(?TableFactory $tableFactory)
    {
        $tableFactory->drop('posts');
    }
}
