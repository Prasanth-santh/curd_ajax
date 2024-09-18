<?php
require_once "database.php";

$errors = array();
$response = array('success' => false, 'errors' => $errors);

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $id = $_POST['id'] ?? ''; // Include ID for update

    // Validate fields
    if (empty($fname) || empty($lname) || empty($email) || empty($gender) || empty($dob) || empty($age) || empty($mobile) || empty($address)) {
        array_push($errors, "All fields are required");
    }

    // Insert or update data
    if (count($errors) == 0) {
        if ($id) {
            // Update existing record
            $sql = "UPDATE users SET firstname='$fname', lastname='$lname', email='$email', gender='$gender', dob='$dob', age='$age', mobile='$mobile', address='$address' WHERE id='$id'";
        } else {
            // Insert new record
            // Check for duplicate email, excluding the current record if updating
            $emailCheckSql = $id ? "SELECT * FROM users WHERE email='$email' AND id != '$id'" : "SELECT * FROM users WHERE email='$email'";
            $result = mysqli_query($conn, $emailCheckSql);
            if (mysqli_num_rows($result) > 0) {
                array_push($errors, "Email already exists");
            }
            $sql = "INSERT INTO users (firstname, lastname, email, gender, dob, age, mobile, address)
                    VALUES ('$fname', '$lname', '$email', '$gender', '$dob', '$age', '$mobile', '$address')";
        }

        if (mysqli_query($conn, $sql)) {
            $response['success'] = true;
        } else {
            array_push($errors, "Database error: Could not save data.");
        }
    }

    // Return response as JSON
    $response['errors'] = $errors;
    echo json_encode($response);
}
?>
