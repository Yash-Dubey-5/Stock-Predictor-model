<?php
// register.php
header('Content-Type: application/json');

// Database connection parameters
$host = 'localhost';
$dbname = 'logindb';
$username = 'root';
$password = '';

// Initialize response array
$response = array('success' => false, 'message' => '');

try {
    // Create a new PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get POST data
    $user = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $pass = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Validate input
    if (empty($user) || empty($pass) || empty($email)) {
        $response['message'] = 'All fields are required';
        echo json_encode($response);
        exit;
    }
    
    // Validate username length
    if (strlen($user) < 4) {
        $response['message'] = 'Username must be at least 4 characters long';
        echo json_encode($response);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format';
        echo json_encode($response);
        exit;
    }
    
    // Validate password length
    if (strlen($pass) < 8) {
        $response['message'] = 'Password must be at least 8 characters long';
        echo json_encode($response);
        exit;
    }
    
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $user);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $response['message'] = 'Username already exists';
        echo json_encode($response);
        exit;
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $response['message'] = 'Email already in use';
        echo json_encode($response);
        exit;
    }
    
    // Hash password for security
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
    
    // Insert new user into database
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
    $stmt->bindParam(':username', $user);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    // Set success response
    $response['success'] = true;
    $response['message'] = 'Registration successful! You can now login.';
    
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

// Return JSON response
echo json_encode($response);
?>