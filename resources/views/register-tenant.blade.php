<!DOCTYPE html>
<html>
<head>
    <title>Register Tenant</title>
</head>
<body>
    <h1>Register Your Company</h1>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <form method="POST" action="/register-tenant">
        @csrf
        <input type="text" name="name" placeholder="Company Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="location" placeholder="Location" required><br><br>
        <input type="text" name="domain" placeholder="Domain (e.g., company.localhost)" required><br><br>
        <input type="text" name="contact_number" placeholder="Contact Number" required><br><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
