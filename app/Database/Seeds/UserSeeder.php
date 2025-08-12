<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            // Admin user
            [
                'email' => 'admin@lms.edu',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'first_name' => 'System',
                'last_name' => 'Admin',
                'role' => 'admin',
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            // Instructor users
            [
                'email' => 'instructor1@lms.edu',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role' => 'instructor',
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'email' => 'instructor2@lms.edu',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'role' => 'instructor',
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            // Student users
            [
                'email' => 'student1@lms.edu',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'role' => 'student',
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'email' => 'student2@lms.edu',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Bob',
                'last_name' => 'Williams',
                'role' => 'student',
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'email' => 'student3@lms.edu',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Charlie',
                'last_name' => 'Brown',
                'role' => 'student',
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
        
        echo "Successfully seeded users table.\n";
    }
}
