<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "patrons";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : null;
    $sex = isset($_POST['sex']) ? trim($_POST['sex']) : '';
    $house_number = isset($_POST['house_number']) ? trim($_POST['house_number']) : '';
    $country = isset($_POST['country']) ? trim($_POST['country']) : '';
    $mobile_phone = isset($_POST['mobile_phone']) ? trim($_POST['mobile_phone']) : '';
    $secondary_phone = isset($_POST['secondary_phone']) ? trim($_POST['secondary_phone']) : null;
    $other_phone = isset($_POST['other_phone']) ? trim($_POST['other_phone']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $secondary_email = isset($_POST['secondary_email']) ? trim($_POST['secondary_email']) : null;
    $primary_contact_method = isset($_POST['primary_contact_method']) ? trim($_POST['primary_contact_method']) : '';
    $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
    $college = isset($_POST['college']) ? trim($_POST['college']) : '';
    $course = isset($_POST['course']) ? trim($_POST['course']) : '';
    $registration_date = isset($_POST['registration_date']) ? $_POST['registration_date'] : null;
    $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : null;

    // Initialize an array to collect errors
    $errors = [];

    // Validate required fields
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($birthday)) {
        $errors[] = "Birthday is required.";
    }
    if (empty($sex)) {
        $errors[] = "Sex is required.";
    }
    if (empty($house_number)) {
        $errors[] = "House Number is required.";
    }
    if (empty($country)) {
        $errors[] = "Country is required.";
    }
    if (empty($mobile_phone)) {
        $errors[] = "Mobile Phone is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (empty($primary_contact_method)) {
        $errors[] = "Primary Contact Method is required.";
    }
    if (empty($card_number)) {
        $errors[] = "Card Number is required.";
    }
    if (empty($college)) {
        $errors[] = "College is required.";
    }
    if (empty($course)) {
        $errors[] = "Course is required.";
    }
    if (empty($registration_date)) {
        $errors[] = "Registration Date is required.";
    }
    if (empty($expiry_date)) {
        $errors[] = "Expiry Date is required.";
    }

    // If there are no errors, proceed to insert into the database
    if (empty($errors)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO patrons (name, birthday, sex, house_number, country, mobile_phone, secondary_phone, other_phone, email, secondary_email, primary_contact_method, card_number, college, course, registration_date, expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters (assuming all fields are strings; adjust types as necessary)
        $stmt->bind_param(
            "ssssssssssssssss",
            $conn->real_escape_string($name),
            $birthday,
            $conn->real_escape_string($sex),
            $conn->real_escape_string($house_number),
            $conn->real_escape_string($country),
            $conn->real_escape_string($mobile_phone),
            $secondary_phone,
            $other_phone,
            $conn->real_escape_string($email),
            $secondary_email,
            $conn->real_escape_string($primary_contact_method),
            $conn->real_escape_string($card_number),
            $conn->real_escape_string($college),
            $conn->real_escape_string($course),
            $registration_date,
            $expiry_date
        );

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to a success page or display a success message
            header("Location: success.html");
            exit();
        } else {
            // Handle errors (e.g., duplicate entries)
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Patron</title>
     <style>
         body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        #registrationForm {
            max-width: 800px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="tel"], input[type="email"], input[type="date"], select {
            width: 25%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button[type="submit"] {
            grid-column: span 2;
            background-color: #440202;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #151515;
        }
        @media (max-width: 200px) {
            #registrationForm {
                grid-template-columns: 1fr;
            }
            button[type="submit"] {
                grid-column: span 1;
            }
        }
    </style>
   
</head>
<body>
    <header>
        <div class="container">
            <h1>Register as a Patron</h1>
        </div>
    </header>
    <center>
    <div class="container">
        <form action="register.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" required><br><br>

            <label for="sex">Sex:</label>
            <select id="sex" name="sex" required>
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br><br>

            <label for="house_number">House Number:</label>
            <input type="text" id="house_number" name="house_number" required><br><br>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required><br><br>

            <label for="mobile_phone">Mobile Phone:</label>
            <input type="tel" id="mobile_phone" name="mobile_phone" required><br><br>

            <label for="secondary_phone">Secondary Phone:</label>
            <input type="tel" id="secondary_phone" name="secondary_phone"><br><br>

            <label for="other_phone">Other Phone:</label>
            <input type="tel" id="other_phone" name="other_phone"><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="secondary_email">Secondary Email:</label>
            <input type="email" id="secondary_email" name="secondary_email"><br><br>

            <label for="primary_contact_method">Primary Contact Method:</label>
            <select id="primary_contact_method" name="primary_contact_method" required>
                <option value="">Select</option>
                <option value="Email">Email</option>
                <option value="Phone">Phone</option>
            </select><br><br>

            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required><br><br>

            <label for="college">College:</label>
            <input type="text" id="college" name="college" required><br><br>

            <label for="course">Course:</label>
            <input type="text" id="course" name="course" required><br><br>

            <label for="registration_date">Registration Date:</label>
            <input type="date" id="registration_date" name="registration_date" required><br><br>

            <label for="expiry_date">Expiry Date:</label>
            <input type="date" id="expiry_date" name="expiry_date" required><br><br>

            <button type="submit">Register</button>
        </form>
    </div></center>
</body>
</html>
