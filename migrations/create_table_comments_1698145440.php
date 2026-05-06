<?php

use Quantum\Database\Factories\TableFactory;
use Quantum\Migration\Migration;

class Create_table_comments_1698145440 extends Migration
{
    public function up(?TableFactory $tableFactory): void
    {
        $table = $tableFactory->create('comments');
        $table->addColumn('id', 'integer')->primary()->autoIncrement();
        $table->addColumn('uuid', 'char', 36);
        $table->addColumn('post_uuid', 'char', 36)->index();
        $table->addColumn('user_uuid', 'char', 36)->index();
        $table->addColumn('content', 'text');
        $table->addColumn('created_at', 'timestamp');
    }

    public function down(?TableFactory $tableFactory): void
    {
        $tableFactory->drop('comments');
    }
}
