<?php
require_once "database.php";

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
$id=1;
while ($row = mysqli_fetch_array($result)) {
    echo "<tr id='row-{$row['id']}'>
            <td> $id</td>
            <td>{$row['firstname']}</td>
            <td>{$row['lastname']}</td>
            <td>{$row['email']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['dob']}</td>
            <td>{$row['age']}</td>
            <td>{$row['mobile']}</td>
            <td>{$row['address']}</td>
            <td>
                <button class='btn btn-success'><a href='#' data-id='{$row['id']}' id='edit'>edit</a></button>
                <button class='btn btn-danger'><a href='#' data-id='{$row['id']}' id='del'>Remove</a></button>
            </td>
        </tr>";
        $id++;
}

?>
