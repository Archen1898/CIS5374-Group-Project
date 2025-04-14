<!DOCTYPE html>
<html>
<head>
  <title>Login</title>

    <style>
    body {
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-family: 'Courier New', Courier, monospace;
        justify-content: center;
    }
    input {
        margin-bottom: 10px;
        padding: 10px 20px;
        width: 20vw;
    }

    button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    </style>

</head>
<body>
  <h2>Enter Your Name</h2>




  <div class="container mt-5">
        <h2 class="text-center">Login</h2>

        <form id="loginForm">
            <!-- Email input -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <!-- Password input -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div id="errorMessage" class="mt-3 text-danger" style="display:none;"></div>
    </div>

    <script>
        // Listen for the form submission
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();  // Prevent the default form submission

            // Get email and password values
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Prepare the data to send
            const data = {
                email: email,
                password: password
            };

            // Make a POST request to the login endpoint using Fetch
            fetch('/api/login-user', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
            if (data.status === 'success') {
                // Handle successful login and redirect
                localStorage.setItem('access_token', data.token);
                console.log('Access token:', data);
                console.log('Setting user_id:', data.user_id);
                localStorage.setItem('user_id', data.user_id);
                //localStorage.setItem('user_id', '1234');
                window.location.href = data.redirect;  // This will redirect the browser
            }
})
.catch(error => {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.textContent = 'An error occurred during login. Please try again.';
    errorMessage.style.display = 'block';
});
        });
    </script>
</body>
</html>