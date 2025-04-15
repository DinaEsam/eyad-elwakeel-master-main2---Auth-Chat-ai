<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            color: green;
            font-size: 14px;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        .role {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form id="loginForm">
        <input type="email" id="email" placeholder="Email" required />
        <input type="password" id="password" placeholder="Password" required />
        <button type="submit">Login</button>
    </form>
    <p class="message" id="message"></p>
    <p class="error" id="error"></p>
    <p class="role" id="role"></p>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Get form values
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        const loginData = {
            email: email,
            password: password
        };

        // Send login request to API
        try {
            const response = await fetch('http://127.0.0.1:8000/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(loginData)
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('message').textContent = 'Login successful!';
                document.getElementById('error').textContent = '';

                // Store the token and role for later use
                localStorage.setItem('auth_token', data.token);
                localStorage.setItem('user_role', data.user.role);
                
                // Display the role of the logged-in user
                document.getElementById('role').textContent = 'Logged in as: ' + data.user.role;

                // Log the user info and token to the console (for testing purposes)
                console.log('User:', data.user);
                console.log('Token:', data.token);
            } else {
                document.getElementById('error').textContent = data.message || 'Login failed';
                document.getElementById('message').textContent = '';
                document.getElementById('role').textContent = '';
            }
        } catch (error) {
            document.getElementById('error').textContent = 'An error occurred. Please try again.';
            document.getElementById('message').textContent = '';
            document.getElementById('role').textContent = '';
        }
    });
</script>

</body>
</html>
