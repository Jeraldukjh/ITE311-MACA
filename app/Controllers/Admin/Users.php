<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function index()
    {
        $this->requireRole('admin');

        $db = \Config\Database::connect();
        $users = $db->table('users')
            ->select('id, name, email, role, is_active, created_at')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->render('admin/users/index', [
            'title' => 'Manage Users',
            'users' => $users,
        ]);
    }

    public function create()
    {
        $this->requireRole('admin');

        return $this->render('admin/users/create', [
            'title' => 'Create User',
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        $this->requireRole('admin');

        if (!$this->request->is('post')) {
            return redirect()->to('/admin/users')->with('error', 'Invalid request method.');
        }

        if (!$this->request->getPost('submit')) {
            return redirect()->back()->with('error', 'Invalid form submission.');
        }

        $input = $this->request->getPost();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => [
                'label' => 'Full Name',
                'rules' => 'required|min_length[3]|max_length[50]|regex_match[/^[\p{L}\s\'\-\.]+$/u]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[100]|is_unique[users.email]',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]|max_length[255]',
            ],
            'password_confirm' => [
                'label' => 'Password Confirmation',
                'rules' => 'required|matches[password]',
            ],
            'role' => [
                'label' => 'Role',
                'rules' => 'required|in_list[student,teacher]',
            ],
        ]);

        if (!$validation->run($input)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();

        $userData = [
            'name' => esc($input['name']),
            'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
            'password' => password_hash($input['password'], PASSWORD_BCRYPT),
            'role' => $input['role'],
            'is_active' => isset($input['is_active']) ? 1 : 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $builder = $db->table('users');
            if ($builder->insert($userData)) {
                return redirect()->to('/admin/users')->with('success', 'User created successfully.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin create user error: ' . $e->getMessage());
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again.');
    }

    public function edit($id)
    {
        $this->requireRole('admin');

        $db = \Config\Database::connect();
        $user = $db->table('users')
            ->select('id, name, email, role, is_active')
            ->where('id', (int)$id)
            ->get()
            ->getRowArray();

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        return $this->render('admin/users/edit', [
            'title' => 'Edit User',
            'user' => $user,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function update($id)
    {
        $this->requireRole('admin');

        if (!$this->request->is('post')) {
            return redirect()->to('/admin/users')->with('error', 'Invalid request method.');
        }

        if (!$this->request->getPost('submit')) {
            return redirect()->back()->with('error', 'Invalid form submission.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $user = $builder->where('id', (int)$id)->get()->getRowArray();

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $input = $this->request->getPost();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => [
                'label' => 'Full Name',
                'rules' => 'required|min_length[3]|max_length[50]|regex_match[/^[\p{L}\s\'\-\.]+$/u]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[100]|is_unique[users.email,id,' . (int)$id . ']',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'permit_empty|min_length[8]|max_length[255]',
            ],
            'password_confirm' => [
                'label' => 'Password Confirmation',
                'rules' => 'permit_empty|matches[password]',
            ],
            'role' => [
                'label' => 'Role',
                'rules' => 'required|in_list[student,teacher,admin]',
            ],
        ]);

        if (!$validation->run($input)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $updateData = [
            'name' => esc($input['name']),
            'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
            'role' => $input['role'],
            'is_active' => isset($input['is_active']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($input['password'])) {
            $updateData['password'] = password_hash($input['password'], PASSWORD_BCRYPT);
        }

        try {
            $builder->where('id', (int)$id)->update($updateData);
            return redirect()->to('/admin/users')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Admin update user error: ' . $e->getMessage());
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again.');
    }

    public function delete($id)
    {
        $this->requireRole('admin');

        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $user = $builder->where('id', (int)$id)->get()->getRowArray();
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        // Optional: prevent deleting yourself
        $currentUserId = session()->get('userID');
        if ($currentUserId && (int)$currentUserId === (int)$id) {
            return redirect()->to('/admin/users')->with('error', 'You cannot delete your own account.');
        }

        try {
            $builder->where('id', (int)$id)->delete();
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Admin delete user error: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Failed to delete user. Please try again.');
        }
    }
}
