<?php
include('includes/config.php');

$errors = [];

if(isset($_POST['submit'])) {
    $email = htmlspecialchars(trim($_POST['email']));
    $oldPassword = htmlspecialchars(trim($_POST['old_password']));
    $newPassword = htmlspecialchars(trim($_POST['new_password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password']));

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate old password
    if (empty($oldPassword)) {
        $errors[] = "Old Password is required.";
    }

    // Validate new password
    if (empty($newPassword)) {
        $errors[] = "New Password is required.";
    } elseif (strlen($newPassword) < 6) {
        $errors[] = "New Password must be at least 6 characters.";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors[] = "Confirm Password is required.";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "Confirm Password does not match New Password.";
    }

    if (empty($errors)) {
        // Check if the email and old password match a user in the database
        $query = "SELECT * FROM user WHERE U_Email = ? AND U_Password = ?";
        $stmt = $mysqli->prepare($query);
        $hashedOldPassword = md5($oldPassword); // Assuming old passwords are stored as MD5 hash
        $stmt->bind_param('ss', $email, $hashedOldPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update the user's password in the database
            $hashedNewPassword = md5($newPassword); // Hash the new password
            $updateQuery = "UPDATE user SET U_Password = ? WHERE U_Email = ?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('ss', $hashedNewPassword, $email);
            if ($updateStmt->execute()) {
                echo "<script>alert('Your password has been successfully updated.');</script>";
                header('Refresh:0.1; url=user_login.php'); // Redirect to login page or appropriate page
                exit();
            } else {
                $errors[] = "Failed to update password. Please try again later.";
            }
        } else {
            $errors[] = "Incorrect email or old password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url('photo/okay.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Lato', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .signup-page {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .form-content {
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .form-content h2 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        .form-content .form-group {
            margin-bottom: 20px;
        }
        .form-content label {
            font-weight: bold;
            color: #333;
        }
        .form-content .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .form-content .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .form-content .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .form-content .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .form-content .btn-block {
            padding: 10px 0;
            font-size: 16px;
        }
        .text-muted {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="signup-page">
        <div class="form-content">
            <h2>Password Reset</h2>
            <?php
            if (!empty($errors)) {
                echo '<div class="alert alert-danger">';
                foreach ($errors as $error) {
                    echo '<p>' . $error . '</p>';
                }
                echo '</div>';
            }
            ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" required>
                </div>
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Your Old Password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter Your New Password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Your New Password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-danger btn-block">Reset Password</button>
                <a href="index.php" class="btn btn-primary btn-block">Back</a>
            </form>
        </div>
    </div>
</body>
</html>

