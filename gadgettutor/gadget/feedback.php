<?php
// Database connection parameters
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'dbgadget';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

$feedback_message = ""; // Initialize feedback message variable

// Check if the user has already submitted feedback
$u_id = $_SESSION['id']; // Assuming 'id' is the session variable holding user ID
$query_check_feedback = "SELECT COUNT(*) as count FROM feedback WHERE U_ID = ?";
$stmt_check_feedback = $conn->prepare($query_check_feedback);
$stmt_check_feedback->bind_param('i', $u_id);
$stmt_check_feedback->execute();
$result_check_feedback = $stmt_check_feedback->get_result();
$row_check_feedback = $result_check_feedback->fetch_assoc();
$count_feedback = $row_check_feedback['count'];

if ($count_feedback > 0) {
    $feedback_message = "You have already submitted feedback.";
} else {
    if (isset($_POST['submit'])) {
        // Sanitize and fetch form data
        $f_desc = trim($_POST['f_desc']);

        // Validate inputs (server-side validation)
        if (empty($f_desc)) {
            $feedback_message = "Feedback description cannot be empty.";
        } else {
            // Prepare SQL statement
            $query = "INSERT INTO feedback (F_Desc, F_Date, U_ID) VALUES (?, CURRENT_TIMESTAMP, ?)";

            // Check if the prepare() call was successful
            $stmt = $conn->prepare($query);
            if ($stmt === false) {
                $feedback_message = "Error preparing statement: " . $conn->error;
            } else {
                // Bind parameters and execute SQL statement
                $stmt->bind_param('si', $f_desc, $u_id);
                if ($stmt->execute()) {
                    $feedback_message = "Feedback submitted successfully.";
                    // Redirect to another page or refresh
                    header('Refresh:0.1; url=gadgetview.php');
                    exit; // Exit after header redirect
                } else {
                    $feedback_message = "Error executing statement: " . $stmt->error;
                }

                $stmt->close(); // Close statement
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
            font-size: 14px;
            min-height: 100px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        input[type="submit"], input[type="button"] {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: calc(50% - 10px);
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        input[type="button"] {
            background-color: #f44336;
            color: white;
        }
        input[type="button"]:hover {
            background-color: #d32f2f;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Your Feedback</h2>
        <?php if (!empty($feedback_message)): ?>
            <p class="message"><?php echo htmlspecialchars($feedback_message); ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="f_desc">Your Feedback:</label>
            <textarea id="f_desc" name="f_desc" rows="6" required></textarea>
            <div class="button-container">
                <input type="submit" name="submit" value="Submit Feedback">
                <input type="button" value="Cancel" onclick="window.location.href='gadgetview.php';">
            </div>
        </form>
    </div>
</body>
</html>
