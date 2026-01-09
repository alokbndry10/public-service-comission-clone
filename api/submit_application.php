<?php
/**
 * Submit Application API
 * 
 * POST endpoint to submit job application
 * Accepts multipart form data with file upload
 * Returns JSON response with success status
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $application_id = $_POST['application_id'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        
        // Validate required fields
        if (empty($application_id) || empty($full_name) || empty($email) || empty($phone)) {
            echo json_encode([
                'success' => false,
                'message' => 'सबै आवश्यक फिल्डहरू भर्नुहोस्'
            ]);
            exit;
        }
        
        // Handle file upload
        $document_path = '';
        if (isset($_FILES['document']) && $_FILES['document']['error'] === 0) {
            $upload_dir = '../uploads/applications/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Validate file type (PDF, DOC, DOCX only)
            $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!in_array($_FILES['document']['type'], $allowed_types)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'कृपया PDF वा Word फाइल मात्र अपलोड गर्नुहोस्'
                ]);
                exit;
            }
            
            // Validate file size (max 5MB)
            if ($_FILES['document']['size'] > 5 * 1024 * 1024) {
                echo json_encode([
                    'success' => false,
                    'message' => 'फाइल साइज ५ MB भन्दा कम हुनुपर्छ'
                ]);
                exit;
            }
            
            $file_ext = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '_' . time() . '.' . $file_ext;
            $document_path = $upload_dir . $file_name;
            
            if (!move_uploaded_file($_FILES['document']['tmp_name'], $document_path)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'फाइल अपलोड गर्न असफल'
                ]);
                exit;
            }
        } else if (isset($_FILES['document'])) {
            echo json_encode([
                'success' => false,
                'message' => 'फाइल आवश्यक छ'
            ]);
            exit;
        }
        
        // Insert application
        $query = "INSERT INTO user_applications 
                  (application_id, full_name, email, phone, document_path) 
                  VALUES (:app_id, :name, :email, :phone, :doc_path)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':app_id', $application_id);
        $stmt->bindParam(':name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':doc_path', $document_path);
        
        if ($stmt->execute()) {
            $newId = $db->lastInsertId();
            echo json_encode([
                'success' => true,
                'message' => 'आवेदन सफलतापूर्वक पेश गरियो। आवेदन नम्बर: ' . $newId,
                'application_id' => $newId,
                'view_link' => 'check_applications.php'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'आवेदन पेश गर्न असफल'
            ]);
        }
    } catch(PDOException $e) {
        error_log("PDO Exception in submit_application.php: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'आवेदन प्रक्रिया विफल'
        ]);
    } catch(Exception $e) {
        error_log("Exception in submit_application.php: " . $e->getMessage());
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
