<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function get()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
                'csrf' => $this->getCsrfData(),
            ])->setStatusCode(401);
        }

        $userId = $this->userData['id'];
        $count = $this->notificationModel->getUnreadCount($userId);
        $list = $this->notificationModel->getNotificationsForUser($userId, 5);

        return $this->response->setJSON([
            'success' => true,
            'count' => $count,
            'notifications' => $list,
            'csrf' => $this->getCsrfData(),
        ]);
    }

    public function mark_as_read($id)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized',
                'csrf' => $this->getCsrfData(),
            ])->setStatusCode(401);
        }

        $id = (int) $id;
        if ($id <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid ID',
                'csrf' => $this->getCsrfData(),
            ])->setStatusCode(400);
        }

        $updated = $this->notificationModel->markAsRead($id);

        return $this->response->setJSON([
            'success' => (bool)$updated,
            'csrf' => $this->getCsrfData(),
        ]);
    }
}
