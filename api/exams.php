<?php
/**
 * Exam Schedules API
 * 
 * GET endpoint to fetch exam schedules
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT id, exam_name, exam_date, exam_time, description, created_at
              FROM exam_schedules 
              WHERE exam_date >= CURDATE()
              ORDER BY exam_date ASC, exam_time ASC";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $exams
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching exam schedules: ' . $e->getMessage()
    ]);
}
?>
