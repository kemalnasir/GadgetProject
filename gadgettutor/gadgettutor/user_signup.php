<?php
include('includes/config.php');

$errors = [];

if(isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phonenum = htmlspecialchars(trim($_POST['phonenum']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Simple validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($phonenum)) {
        $errors[] = "Phone Number is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        // Default role for new users
        $role = 'user';

        // Hash the password using md5
        $password = md5($_POST['password']);

        // Prepare insert query
        $query = "INSERT INTO user (U_Name, U_Email, U_PhoneNo, U_Password, U_Roles) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);

        // Bind parameters
        $stmt->bind_param('sssss', $name, $email, $phonenum, $password, $role);

        // Execute statement
        if ($stmt->execute()) {
            echo "<script>alert('Thank you, Your Sign Up details have been successfully submitted!');</script>";
            // Redirect to login page or wherever appropriate
            header('Refresh:0.1; url=user_login.php');
            exit();
        } else {
            echo "<script>alert('Failed to add new student data');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
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
            <h2>Sign Up</h2>
            <?php
            // Display validation errors if any
            if (!empty($errors)) {
                echo '<div class="alert alert-danger">';
                foreach ($errors as $error) {
                    echo '<p>' . $error . '</p>';
                }
                echo '</div>';
            }
            ?>
            <form action="" method="post" id="signup-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Your Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="phonenum">Phone Number</label>
                    <input type="tel" class="form-control" id="phonenum" name="phonenum" placeholder="Enter Your Phone Number" value="<?php echo isset($_POST['phonenum']) ? $_POST['phonenum'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-danger btn-block">Sign Up</button>
                <div class="text-muted mt-3">
                    <small>Already have an account? <a href="user_login.php">Sign In</a></small>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        // Basic form validation using JavaScript
        document.getElementById('signup-form').addEventListener('submit', function(event) {
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var phonenum = document.getElementById('phonenum').value.trim();
            var password = document.getElementById('password').value.trim();

            var errorMessages = [];

            if (name === '') {
                errorMessages.push('Name is required.');
            }
            if (email === '') {
                errorMessages.push('Email is required.');
            } else if (!isValidEmail(email)) {
                errorMessages.push('Invalid email format.');
            }
            if (phonenum === '') {
                errorMessages.push('Phone Number is required.');
            }
            if (password === '') {
                errorMessages.push('Password is required.');
            }

            if (errorMessages.length > 0) {
                event.preventDefault(); // Prevent form submission
                displayErrorMessages(errorMessages);
            }
        });

        // Function to validate email format
        function isValidEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        // Function to display error messages
        function displayErrorMessages(messages) {
            var errorContainer = document.createElement('div');
            errorContainer.classList.add('alert', 'alert-danger');
            errorContainer.setAttribute('role', 'alert');
            var errorMessageList = document.createElement('ul');

            messages.forEach(function(message) {
                var errorMessageItem = document.createElement('li');
                errorMessageItem.textContent = message;
                errorMessageList.appendChild(errorMessageItem);
            });

            errorContainer.appendChild(errorMessageList);

            var form = document.getElementById('signup-form');
            form.insertBefore(errorContainer, form.firstChild);
        }
    </script>
</body>
</html>
