<?php

use Quantum\Migration\QtMigration;
use Quantum\Factory\TableFactory;
use Quantum\Libraries\Database\Schema\Type;
use Quantum\Libraries\Database\Schema\Key;


class Create_table_users_1669639740 extends QtMigration
{

    public function up(?TableFactory $tableFactory)
    {
        $table = $tableFactory->create('users');
        $table->addColumn('id', 'integer')->primary()->autoIncrement();
        $table->addColumn('uuid', 'char', 36);
        $table->addColumn('firstname', 'varchar', 255);
        $table->addColumn('lastname', 'varchar', 255);
        $table->addColumn('role', 'varchar', 255);
        $table->addColumn('email', 'varchar', 255);
        $table->addColumn('password', 'varchar', 255);
        $table->addColumn('activation_token', 'varchar', 255)->nullable();
        $table->addColumn('remember_token', 'varchar', 255)->nullable();
        $table->addColumn('access_token', 'varchar', 255)->nullable();
        $table->addColumn('refresh_token', 'varchar', 255)->nullable();
        $table->addColumn('reset_token', 'varchar', 255)->nullable();
        $table->addColumn('otp', 'integer')->nullable();
        $table->addColumn('otp_token', 'varchar', 255)->nullable();
        $table->addColumn('otp_expires', 'timestamp')->nullable();
    }

    public function down(?TableFactory $tableFactory)
    {
        $tableFactory->drop('users');
    }
}
