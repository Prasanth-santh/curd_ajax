<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "database.php";

    $id = $_POST['id'] ?? '';

    if (!empty($id)) {
        // Prepare the SQL DELETE query
        $sql = "DELETE FROM users WHERE id='$id'";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
    }
}
?>
