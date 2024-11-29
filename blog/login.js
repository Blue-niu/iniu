document.getElementById('login-button').addEventListener('click', function(event) {
    event.preventDefault();  // Prevent form submission and page refresh

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    let isValid = true;

    // Validate username and password
    if (username !== 'admin') {
        isValid = false;  // Invalid username
    }

    if (password !== '1234') {
        isValid = false;  // Invalid password
    }

    // If valid, navigate to the dashboard
    if (isValid) {
        window.location.href = 'dashboard.html';  // Redirect to the dashboard page
    } else {
        alert('用户名或密码错误');  // Show error message
    }
});
