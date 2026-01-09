<?php
/**
 * Publications API
 * 
 * GET endpoint to fetch publications
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$category = isset($_GET['category']) ? $_GET['category'] : '';
$offset = ($page - 1) * $limit;

try {
    $query = "SELECT id, title, description, publish_date, file_path, category, created_at
              FROM publications ";
    
    if (!empty($category)) {
        $query .= "WHERE category = :category ";
    }
    
    $query .= "ORDER BY publish_date DESC
               LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($query);
    
    if (!empty($category)) {
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    }
    
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM publications";
    if (!empty($category)) {
        $countQuery .= " WHERE category = :category";
    }
    
    $countStmt = $db->prepare($countQuery);
    if (!empty($category)) {
        $countStmt->bindParam(':category', $category, PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalRecords = $countStmt->fetch()['total'];

    echo json_encode([
        'success' => true,
        'data' => $publications,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $totalRecords,
            'totalPages' => ceil($totalRecords / $limit)
        ]
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching publications: ' . $e->getMessage()
    ]);
}
?>
