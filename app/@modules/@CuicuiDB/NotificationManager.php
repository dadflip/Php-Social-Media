<?php

class NotificationManager {
    private $db;

    // Constructor
    public function __construct($db) {
        $this->db = $db;
    }

    // Insert a notification into the 'notifications' table
    public function insertNotification($userId, $notificationDate, $notificationTitle, $notificationText, $notificationType) {
        $insertNotificationQuery = "INSERT INTO notifications (users_uid, c_datetime, title, text_content, notification_type) VALUES (?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($insertNotificationQuery);
        $statement->bind_param("issss", $userId, $notificationDate, $notificationTitle, $notificationText, $notificationType);
        $success = $statement->execute();
        $statement->close();
        return $success;
    }

    // Update a notification in the 'notifications' table
    public function updateNotification($notificationId, $notificationType, $notificationTitle, $notificationText) {
        $updateNotificationQuery = "UPDATE notifications SET notification_type = ?, title = ?, text_content = ? WHERE notification_id = ?";
        $statement = $this->db->prepare($updateNotificationQuery);
        $statement->bind_param("sssi", $notificationType, $notificationTitle, $notificationText, $notificationId);
        $success = $statement->execute();
        $statement->close();
        return $success;
    }

    // Delete a notification from the 'notifications' table
    public function deleteNotification($notificationId) {
        $deleteNotificationQuery = "DELETE FROM notifications WHERE notification_id = ?";
        $statement = $this->db->prepare($deleteNotificationQuery);
        $statement->bind_param("i", $notificationId);
        $success = $statement->execute();
        $statement->close();
        return $success;
    }

    // Retrieve notifications by user ID
    public function getNotificationsByUserId($userId) {
        $getNotificationsQuery = "SELECT * FROM notifications WHERE users_uid = ?";
        $statement = $this->db->prepare($getNotificationsQuery);
        $statement->bind_param("i", $userId);
        $statement->execute();
        $result = $statement->get_result();
        $notifications = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        return $notifications;
    }

    public function getNotificationIdsByUserId($userId) {
        $notificationIds = [];
        $getNotificationsQuery = "SELECT id FROM notifications WHERE users_uid = ?";
        $statement = $this->db->prepare($getNotificationsQuery);
        $statement->bind_param("i", $userId);
        $statement->execute();
        $result = $statement->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $notificationIds[] = $row['id'];
        }
        
        $statement->close();
        return $notificationIds;
    }

    public function getUserNotifications($userId) {
        // Marquer les notifications comme lues lors de l'accès à la page de notifications
        $updateQuery = "UPDATE notifications SET is_read = 1 WHERE users_uid = ? AND is_read = 0";
        $updateStmt = $this->db->prepare($updateQuery);
        $updateStmt->bind_param("i", $userId);
        $updateStmt->execute();
    
        // Récupérer les notifications de l'utilisateur depuis les deux dernières semaines
        $twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-2 weeks'));
        $selectQuery = "SELECT * FROM notifications WHERE users_uid = ? AND c_datetime > ? ORDER BY c_datetime DESC";
        $selectStmt = $this->db->prepare($selectQuery);
        $selectStmt->bind_param("is", $userId, $twoWeeksAgo);
        $selectStmt->execute();
        $result = $selectStmt->get_result();
    
        // Vérifier si la requête a réussi
        if ($result) {
            // Récupérer les résultats sous forme de tableau associatif
            $notifications = $result->fetch_all(MYSQLI_ASSOC);
            return $notifications;
        } else {
            // Gérer l'erreur si la requête échoue
            return [];
        }
    }

    public function countUnreadNotifications($userId) {
        // Récupérer le nombre de notifications non lues de l'utilisateur
        $countQuery = "SELECT COUNT(*) as unread_count FROM notifications WHERE users_uid = ? AND is_read = 0";
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->bind_param("i", $userId);
        $countStmt->execute();
        $result = $countStmt->get_result();
    
        if ($result && $row = $result->fetch_assoc()) {
            return $row['unread_count'];
        } else {
            return 0;
        }
    }
    
    
    // Retrieve notifications by date
    public function getNotificationsByDate($startDate, $endDate) {
        $getNotificationsQuery = "SELECT * FROM notifications WHERE c_datetime BETWEEN ? AND ?";
        $statement = $this->db->prepare($getNotificationsQuery);
        $statement->bind_param("ss", $startDate, $endDate);
        $statement->execute();
        $result = $statement->get_result();
        $notifications = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        return $notifications;
    }

    // Mark notification as read
    public function markNotificationAsRead($notificationId) {
        $markAsReadQuery = "UPDATE notifications SET is_read = 1 WHERE notification_id = ?";
        $statement = $this->db->prepare($markAsReadQuery);
        $statement->bind_param("i", $notificationId);
        $success = $statement->execute();
        $statement->close();
        return $success;
    }

    // Mark notification as unread
    public function markNotificationAsUnread($notificationId) {
        $markAsUnreadQuery = "UPDATE notifications SET is_read = 0 WHERE notification_id = ?";
        $statement = $this->db->prepare($markAsUnreadQuery);
        $statement->bind_param("i", $notificationId);
        $success = $statement->execute();
        $statement->close();
        return $success;
    }
}
