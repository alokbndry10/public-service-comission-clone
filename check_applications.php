<?php
/**
 * Quick check to see applications in database
 */

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h2>Checking user_applications table...</h2>";

try {
    // Check if table exists
    $stmt = $db->query("SHOW TABLES LIKE 'user_applications'");
    if ($stmt->rowCount() == 0) {
        echo "<p style='color:red;'>❌ Table 'user_applications' does NOT exist!</p>";
        echo "<p>Run database-setup.sql in phpMyAdmin first.</p>";
        exit;
    }
    echo "<p style='color:green;'>✓ Table 'user_applications' exists</p>";
    
    // Count total applications
    $stmt = $db->query("SELECT COUNT(*) as total FROM user_applications");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total applications:</strong> " . $count['total'] . "</p>";
    
    // Get all applications
    $stmt = $db->query("SELECT * FROM user_applications ORDER BY id DESC");
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($applications) > 0) {
        echo "<h3>Applications:</h3>";
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr>
                <th>ID</th>
                <th>Application ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Document</th>
                <th>Status</th>
                <th>Submitted At</th>
              </tr>";
        
        foreach ($applications as $app) {
            echo "<tr>";
            echo "<td>" . $app['id'] . "</td>";
            echo "<td>" . $app['application_id'] . "</td>";
            echo "<td>" . $app['full_name'] . "</td>";
            echo "<td>" . $app['email'] . "</td>";
            echo "<td>" . $app['phone'] . "</td>";
            echo "<td>" . ($app['document_path'] ? "Yes" : "No") . "</td>";
            echo "<td>" . $app['status'] . "</td>";
            echo "<td>" . $app['submitted_at'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p style='color:orange;'>⚠ No applications found in the database.</p>";
        echo "<h3>Possible reasons:</h3>";
        echo "<ul>";
        echo "<li>The form submission failed silently</li>";
        echo "<li>Database connection issue</li>";
        echo "<li>The table was cleared or recreated</li>";
        echo "</ul>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color:red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
