<?php

use Quantum\Migration\QtMigration;
use Quantum\Factory\TableFactory;
use Quantum\Libraries\Database\Schema\Type;
use Quantum\Libraries\Database\Schema\Key;


class Create_table_posts_1669639752 extends QtMigration
{

    public function up(?TableFactory $tableFactory)
    {
        $table = $tableFactory->create('posts');
        $table->addColumn('id', 'integer')->primary()->autoIncrement();
        $table->addColumn('uuid', 'char', 36);
        $table->addColumn('user_id', 'integer')->index();
        $table->addColumn('title', 'varchar', 255);
        $table->addColumn('content', 'varchar', 255);
        $table->addColumn('image', 'varchar', 255);
        $table->addColumn('updated_at', 'timestamp');
    }

    public function down(?TableFactory $tableFactory)
    {
        $tableFactory->drop('posts');
    }
}
