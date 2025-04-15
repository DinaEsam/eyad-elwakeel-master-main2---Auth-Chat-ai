<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor API Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <!-- Form Container -->
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-xl font-bold text-center mb-4">Test Doctor API</h2>

        <!-- Admin Token Input -->
        <label class="block font-semibold"> Token:</label>
        <input type="text" id="token" class="w-full p-2 border rounded mb-3" placeholder="Enter Bearer Token">

        <!-- Doctor Form -->
        <label class="block font-semibold">Name:</label>
        <input type="text" id="name" class="w-full p-2 border rounded mb-3" placeholder="Doctor Name">

        <label class="block font-semibold">Email:</label>
        <input type="email" id="email" class="w-full p-2 border rounded mb-3" placeholder="Doctor Email">

        <label class="block font-semibold">Password:</label>
        <input type="password" id="password" class="w-full p-2 border rounded mb-3" placeholder="Password">

        <label class="block font-semibold">Specialty:</label>
        <input type="text" id="specialty" class="w-full p-2 border rounded mb-3" placeholder="Specialty">

        <label class="block font-semibold">Phone:</label>
        <input type="text" id="phone" class="w-full p-2 border rounded mb-3" placeholder="Phone Number">

        <button onclick="addDoctor()" class="bg-blue-500 text-white p-2 w-full rounded hover:bg-blue-600">
            Add Doctor
        </button>

        <!-- Response Section -->
        <div id="response-container" class="mt-4">
            <h3 class="font-semibold">Response:</h3>
            <pre id="response" class="mt-2 p-2 bg-gray-200 rounded max-h-40 overflow-auto text-sm"></pre>
        </div>
    </div>

    <script>
        async function addDoctor() {
            const token = document.getElementById("token").value;
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const specialty = document.getElementById("specialty").value;
            const phone = document.getElementById("phone").value;

            const responseBox = document.getElementById("response");

            if (!token) {
                responseBox.innerText = "⚠️ Please enter an admin token.";
                return;
            }

            // Clear previous response before making a new request
            responseBox.innerText = "Loading...";

            const response = await fetch("http://127.0.0.1:8000/api/doctors", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + token,
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password,
                    specialty: specialty,
                    phone: phone,
                }),
            });

            const result = await response.json();
            responseBox.innerText = JSON.stringify(result, null, 2);
        }
    </script>
</body>
</html>
