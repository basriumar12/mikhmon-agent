<?php
/*
 * Owner Model Class
 * Handles multi-owner SaaS tenants
 */

class Owner {
    private $db;
    
    public function __construct() {
        if (!function_exists('getDBConnection')) {
            require_once(__DIR__ . '/../include/db_config.php');
        }
        $this->db = getDBConnection();
    }
    
    /**
     * Create new owner account
     */
    public function createOwner($data) {
        try {
            $sql = "INSERT INTO owners (username, email, phone, password, status, level) 
                    VALUES (:username, :email, :phone, :password, :status, :level)";
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':status' => $data['status'] ?? 'active',
                ':level' => $data['level'] ?? 'bronze'
            ]);
            
            $ownerId = $this->db->lastInsertId();
            
            return [
                'success' => true,
                'owner_id' => $ownerId,
                'message' => 'Owner account created successfully'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get owner by identifier (username, email or phone)
     */
    public function getOwnerByIdentifier($identifier) {
        $sql = "SELECT * FROM owners WHERE username = :id OR email = :id OR phone = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $identifier]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
