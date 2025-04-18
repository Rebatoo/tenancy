<!DOCTYPE html>
<html>
<head>
    <title>Tenant Details</title>
</head>
<body>
    <h1>Tenant: {{ $tenant->name }}</h1>

    @if($domain)
        <h3>Domain: {{ $domain->domain }}</h3> <!-- Display the domain name -->
    @else
        <p>No domain associated with this tenant.</p>
    @endif
</body>
</html>
