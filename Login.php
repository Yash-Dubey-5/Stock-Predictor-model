<?php
// login.php
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
    $pass = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Validate input
    if (empty($user) || empty($pass)) {
        $response['message'] = 'Username and password are required';
        echo json_encode($response);
        exit;
    }
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->bindParam(':username', $user);
    $stmt->execute();
    
    // Check if user exists
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify password (using password_verify for hashed passwords)
        if (password_verify($pass, $row['password'])) {
            // Start session and set session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            // Set success response
            $response['success'] = true;
            $response['message'] = 'Login successful!';
        } else {
            $response['message'] = 'Invalid password';
        }
    } else {
        $response['message'] = 'User not found';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

// Return JSON response
echo json_encode($response);
?>