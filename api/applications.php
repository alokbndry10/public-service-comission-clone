<?php
/**
 * Applications API
 * 
 * GET endpoint to fetch open job applications
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT id, position_title, advertisement_no, department, 
              total_positions, deadline, status, form_path, requirements, created_at
              FROM applications 
              WHERE status = 'खुला' AND deadline >= CURDATE()
              ORDER BY deadline ASC";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $applications
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching applications: ' . $e->getMessage()
    ]);
}
?>
