<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email'       => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'password'    => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'first_name'  => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'last_name'   => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'role'        => [
                'type'       => 'ENUM',
                'constraint' => ['student', 'instructor', 'admin'],
                'default'    => 'student',
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true); // true = if not exists
    }

    public function down()
    {
        $this->forge->dropTable('users', true); // true = if exists
    }
}
