<?php
/**
 * Contact Form API
 * 
 * POST endpoint to submit contact messages
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get POST data (support both form-data and JSON)
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        
        if (stripos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents("php://input"), true);
            $full_name = $data['full_name'] ?? '';
            $email = $data['email'] ?? '';
            $phone = $data['phone'] ?? '';
            $subject = $data['subject'] ?? '';
            $message = $data['message'] ?? '';
        } else {
            $full_name = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $message = $_POST['message'] ?? '';
        }
        
        // Validate required fields
        if (empty($full_name) || empty($email) || empty($subject) || empty($message)) {
            echo json_encode([
                'success' => false,
                'message' => 'सबै आवश्यक फिल्डहरू भर्नुहोस्'
            ]);
            exit;
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'मान्य इमेल ठेगाना प्रविष्ट गर्नुहोस्'
            ]);
            exit;
        }
        
        // Insert message
        $query = "INSERT INTO contact_messages 
                  (full_name, email, phone, subject, message) 
                  VALUES (:name, :email, :phone, :subject, :message)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'संदेश सफलतापूर्वक पठाइयो। हामी छिट्टै सम्पर्क गरिने छौं।'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'संदेश पठाउन असफल'
            ]);
        }
    } catch(PDOException $e) {
        error_log("PDO Exception in contact.php: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'संदेश प्रक्रिया विफल'
        ]);
    } catch(Exception $e) {
        error_log("Exception in contact.php: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'प्रणाली त्रुटि हुई'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
