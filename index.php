<?php
 if(isset($_GET['id'])){
    $id=$_GET['id'];
    require_once "database.php";
    $sql="SELECT * FROM users WHERE id='$id'";
    $res=mysqli_query($conn,$sql)->fetch_assoc(); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission with AJAX</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to calculate age based on DOB
            showtable();
            function calculateAge(dob) {
                const birthDate = new Date(dob);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            // Set the calculated age in the age input box
            $('#dob').on('change', function() {
                const dob = $(this).val();
                if (dob) {
                    const age = calculateAge(dob);
                    $('#age').val(age);
                }
            });

            // Handle form submission via AJAX
            $('form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: 'back.php', // URL of the PHP script
                    type: 'POST',
                    data: $(this).serialize(), // Serialize form data
                    success: function(response) {
                        const result = JSON.parse(response);
                        const errorDiv = $('.form_errors');
                        errorDiv.html(''); // Clear previous errors

                        if (result.success) {
                            alert('Form submitted successfully!');
                            $('form')[0].reset(); // Reset the form
                           showtable();
                        } else {
                            result.errors.forEach(function(error) {
                                errorDiv.append(`<p>${error}</p>`);
                            });
                        }
                    },
                    error: function() {
                        alert('An error occurred during submission.');
                    }
                });
            });
        });


        $(document).on('click', '#edit', function(event) {
        event.preventDefault(); // Prevent default link behavior

        const id = $(this).data('id'); // Get the id from the data attribute

        $.ajax({
            url: 'edit.php', // URL of the PHP script
            type: 'POST',
            data: { id: id }, // Send the ID to the PHP script
            success: function(response) {
                const result = JSON.parse(response);

                if (result.success) {
                    // Populate the form with the data
                    $('#user_id').val(result.data.id);
                    $('#fname').val(result.data.firstname);
                    $('#lname').val(result.data.lastname);
                    $('#email').val(result.data.email);
                    $('#gender').val(result.data.gender);
                    $('#dob').val(result.data.dob);
                    $('#age').val(result.data.age);
                    $('#mobile').val(result.data.mobile);
                    $('#address').val(result.data.address);
                    
                } else {
                    alert('Failed to load data for editing.');
                }
            },
            error: function() {
                alert('An error occurred during fetching.');
            }
        });
    });


    $(document).on('click', '#del', function(event) {
    event.preventDefault(); // Prevent default link behavior

    const id = $(this).data('id'); // Get the ID from the data attribute

    $.ajax({
        url: 'del.php', // URL of the PHP script
        type: 'POST',
        data: { id: id }, // Send the ID to the PHP script
        success: function(response) {
            const result = JSON.parse(response);

            if (result.success) {
                alert(result.message);
               showtable();
            } else {
                alert(result.message);
            }
        },
        error: function() {
            alert('An error occurred during deletion.');
        }
    });
});

    function showtable(){
        $.ajax({
                url: 'fetch.php', // A separate PHP file to fetch updated table data
                type: 'GET',
                success: function(data) {
                    $('tbody').html(data); // Replace the table body with the updated data
                },
                error: function() {
                    alert('An error occurred while updating the table.');
                }
            });
    }
    </script>
</head>
<body>
    <div class="overall">
        <h1>Grid Crud Operation</h1>
        <div class="container">
            <form action="" class="form" method="post">
                <label for="fname">First Name:</label><br>
                <input type="text" name="fname" id="fname" placeholder="Enter your first name" value="<?php echo isset($_GET['id']) ? $res['firstname'] : ""?>" required><br>
                <label for="lname">Last Name:</label><br>
                <input type="text" name="lname" id="lname" placeholder="Enter your last name" value="<?php echo isset($_GET['id']) ? $res['lastname'] : ""?>" required><br>
                <label for="email">Email:</label><br>
                <input type="text" name="email" id="email" placeholder="Enter your email" value="<?php echo isset($_GET['id']) ? $res['email'] : ""?>" required><br>
                <label for="gender">Gender:</label><br>
                <select name="gender" id="gender"  required>
                    <option value=""><?php echo isset($_GET['id']) ? $res['gender'] : "---------"?></option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="others">Others</option>
                </select><br>
                
                <label for="dob">DOB:</label><br>
                <input type="date" name="dob" id="dob" placeholder="Enter your date of birth" value="<?php echo isset($_GET['id']) ? $res['dob'] : ""?>" required><br>
                <label for="age">Age:</label><br>
                <input type="text" name="age" id="age" placeholder="Enter your age" value="<?php echo isset($_GET['id']) ? $res['age'] : ""?>" readonly><br>
                <label for="mobile">Mobile No:</label><br>
                <input type="text" name="mobile" id="mobile" placeholder="Enter your mobile number" value="<?php echo isset($_GET['id']) ? $res['mobile'] : ""?>" required><br>
                <label for="address">Address:</label><br>
                <textarea name="address" id="address" placeholder="Enter your address" value="" required><?php echo isset($_GET['id']) ? $res['address'] : ""?></textarea><br>
                <input type="hidden" name="id" id="user_id" value="">
                <input type="submit" name="submit" value="Submit">
                <div class="form_errors"></div>
            </form>
        </div>
        <table class="table">
                <thead>
                    <tr>
                        <th>SI</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th> Email </th>
                        <th>Gender</th>
                        <th> DOB </th>
                        <th> Age </th>
                        <th> Mobile </th>
                        <th> Address </th>
                        <th> Action </th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    require_once "database.php";
                    $sql="SELECT * FROM users";
                    $result=mysqli_query($conn,$sql);
                    $id=1;
                    while ($row=mysqli_fetch_array($result)) {
                    $uid=$row['id'];
                    $fname=$row['firstname'];
                    $lname=$row['lastname'];
                    $email=$row['email'];
                    $gender=$row['gender'];
                    $dob=$row['dob'];
                    $age=$row['age'];
                    $mobile=$row['mobile'];
                    $address=$row['address'];

                    
                    ?>
                    <tr>
                        <td><?php echo $id?></td>
                        <td><?php echo $fname?></td>
                        <td><?php echo $lname?></td>
                        <td><?php echo $email?></td>
                        <td><?php echo $gender?></td>
                        <td><?php echo $dob?></td>
                        <td><?php echo $age?></td>
                        <td><?php echo $mobile?></td>
                        <td><?php echo $address?></td>
                        <td><button class="btn btn-success"><a href='#'  data-id="<?php echo $uid ?>" id="edit">edit</a></button>
                        <button class="btn btn-danger"><a href='#'  data-id="<?php echo $uid ?>" id="del">Remove</a></button></td>
                    </tr>
                    <?php $id++; } ?>
                </tbody>
        </table>
    </div>
</body>
</html>
