<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "database.php";

    $id = $_POST['id'] ?? '';
    if (!empty($id)) {
        $sql = "SELECT * FROM users WHERE id='$id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data = mysqli_fetch_assoc($result);
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error fetching data']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
    }
}
?>
