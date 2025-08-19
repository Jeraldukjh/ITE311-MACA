<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => '100'],
            'email'      => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'password'   => ['type' => 'VARCHAR', 'constraint' => '255'],
            'role'       => ['type' => 'ENUM', 'constraint' => ['student', 'instructor', 'admin']],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        $data = [
            'name'       => 'Jraldz',
            'email'      => 'GeraldMacaFernandez@gmail.com',
            'password'   => password_hash('jeraldfern09', PASSWORD_DEFAULT),
            'role'       => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        $db->table('users')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
