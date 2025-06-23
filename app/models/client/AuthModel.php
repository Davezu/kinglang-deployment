<?php
require_once __DIR__ . "/../../../config/database.php";

class ClientAuthModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
    }

    public function findByEmail($email) {
        try {
            $stmt = $this->conn->prepare("SELECT email FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function saveResetToken($email, $token, $expiry) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
            return $stmt->execute([$token, $expiry, $email]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function findByToken($token) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
            $stmt->execute([$token]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updatePassword($token, $passwordHash) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
            return $stmt->execute([$passwordHash, $token]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function emailExist($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([":email" => $email]);
        return $stmt->fetch() ? true : false;
    }

    function isValidPassword($password) {
        $pattern = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    
        return preg_match($pattern, $password);
    }

    public function signup($first_name, $last_name, $company_name, $email, $contact_number, $password) {
        if ($this->emailExist($email)) {
            return "Email already exists.";
        }

        if (!$this->isValidPassword($password)) {
            return "Invalid password.";
        }   

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, company_name, email, contact_number, password) VALUES (:first_name, :last_name, :company_name, :email, :contact_number, :password)");
            $result = $stmt->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":company_name" => $company_name,
                ":email" => $email,
                ":contact_number" => $contact_number,
                ":password" => $hashed_password
            ]);
            
            return "success";
        } catch (PDOException $e) {
            return "Database error.";
        }
    }

    public function login($email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email AND role = 'client'");
            $stmt->execute([":email" => $email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return "Email not found.";
            }

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["email"] = $user["email"];
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["client_name"] = $user["first_name"] . " " . $user["last_name"];
                
                return "success";
            } 
            return "Incorrect password.";
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function googleLogin($email, $first_name, $last_name, $picture = null) {
        try {
            // Check if the user already exists
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([":email" => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // User exists, update their Google profile info if needed
                $stmt = $this->conn->prepare("UPDATE users SET google_id = :google_id, profile_picture = :picture WHERE email = :email");
                $stmt->execute([
                    ":google_id" => "google_" . md5($email),
                    ":picture" => $picture,
                    ":email" => $email
                ]);
                
                // Set session variables
                $_SESSION["email"] = $user["email"];
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["client_name"] = $user["first_name"] . " " . $user["last_name"];
                
                return "success";
            } else {
                // User doesn't exist, create a new account
                // Generate a random secure password for the Google account
                $random_password = bin2hex(random_bytes(8));
                $hashed_password = password_hash($random_password, PASSWORD_BCRYPT);
                
                // Insert the new user
                $stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, email, password, google_id, profile_picture, role) 
                                            VALUES (:first_name, :last_name, :email, :password, :google_id, :picture, 'client')");
                $result = $stmt->execute([
                    ":first_name" => $first_name,
                    ":last_name" => $last_name,
                    ":email" => $email,
                    ":password" => $hashed_password,
                    ":google_id" => "google_" . md5($email),
                    ":picture" => $picture
                ]);
                
                if ($result) {
                    // Get the newly created user ID
                    $user_id = $this->conn->lastInsertId();
                    
                    // Set session variables
                    $_SESSION["email"] = $email;
                    $_SESSION["user_id"] = $user_id;
                    $_SESSION["client_name"] = $first_name . " " . $last_name;
                    
                    return "success";
                } else {
                    return "Failed to create user account.";
                }
            }
        } catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }

    public function getClientID($user_id) {
        try {
            $stmt = $this->conn->prepare("SELECT client_id FROM users WHERE user_id = :user_id");
            $stmt->execute([":user_id" => $user_id]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function getClientInformation() {
        $user_id = $_SESSION["user_id"];
        try {
            $stmt = $this->conn->prepare("SELECT first_name, last_name, email, contact_number, company_name FROM users WHERE user_id = :user_id");
            $stmt->execute([":user_id" => $user_id]);

            $client = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $client;
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function updateClientInformation($first_name, $last_name, $company_name, $contact_number, $email_address, $address = null) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, company_name = :company_name, email = :email, contact_number = :contact_number WHERE user_id = :user_id");
            $stmt->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":company_name" => $company_name,
                ":email" => $email_address,
                ":contact_number" => $contact_number,
                ":user_id" => $_SESSION["user_id"]
            ]);

            $_SESSION["client_name"] = $first_name . " " . $last_name;    

            return "success";
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    /**
     * Update password for a logged-in user (requires current password verification)
     */
    public function updatePasswordForLoggedInUser($user_id, $currentPassword, $newPassword) {
        try {
            // Fetch current hashed password
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $user_id]);
            $hashedPassword = $stmt->fetchColumn();
            if (!$hashedPassword) {
                return ["success" => false, "message" => "User not found."];
            }
            // Verify current password
            if (!password_verify($currentPassword, $hashedPassword)) {
                return ["success" => false, "message" => "Current password is incorrect."];
            }
            // Validate new password
            if (!$this->isValidPassword($newPassword)) {
                return ["success" => false, "message" => "New password does not meet requirements."];
            }
            // Hash new password
            $newHashed = password_hash($newPassword, PASSWORD_BCRYPT);
            // Update password in DB
            $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
            $stmt->execute([':password' => $newHashed, ':user_id' => $user_id]);
            return ["success" => true, "message" => "Password updated successfully."];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }
}
?>