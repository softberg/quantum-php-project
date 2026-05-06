<?php

use Quantum\Database\Factories\TableFactory;
use Quantum\Migration\Migration;

class Create_table_posts_1669639752 extends Migration
{
    public function up(?TableFactory $tableFactory): void
    {
        $table = $tableFactory->create('posts');
        $table->addColumn('id', 'integer')->primary()->autoIncrement();
        $table->addColumn('uuid', 'char', 36);
        $table->addColumn('user_uuid', 'varchar')->index();
        $table->addColumn('title', 'varchar', 255);
        $table->addColumn('content', 'text');
        $table->addColumn('image', 'varchar', 255);
        $table->addColumn('updated_at', 'timestamp');
    }

    public function down(?TableFactory $tableFactory): void
    {
        $tableFactory->drop('posts');
    }
}
