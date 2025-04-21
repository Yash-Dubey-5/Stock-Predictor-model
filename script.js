// script.js
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const messageDiv = document.getElementById('message');
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        // Create FormData object
        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);
        
        // Send AJAX request to check login credentials
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.className = 'success';
                messageDiv.textContent = 'Login successful! Redirecting...';
                
                // Redirect to dashboard after successful login
                setTimeout(() => {
                    window.location.href = 'dashboard.php';
                }, 1500);
            } else {
                messageDiv.className = 'error';
                messageDiv.textContent = data.message;
            }
        })
        .catch(error => {
            messageDiv.className = 'error';
            messageDiv.textContent = 'An error occurred. Please try again.';
            console.error('Error:', error);
        });
    });
});