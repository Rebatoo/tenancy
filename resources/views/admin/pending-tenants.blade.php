<!DOCTYPE html>
<html>
<head>
    <title>Pending Tenants</title>
</head>
<body>
    <h1>Pending Tenant Registrations</h1>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif
    @if(session('error'))
    <p style="color: red">{{ session('error') }}</p>
@endif
    <ul>
        @foreach ($pendingTenants as $tenant)
            <li>
                {{ $tenant->name }} ({{ $tenant->domain }})
                <form method="POST" action="/admin/approve-tenant/{{ $tenant->id }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to approve this tenant?');">
                    @csrf
                    <button type="submit">Approve</button>
                </form>
            </li>
            
        @endforeach
    </ul>
</body>
</html>
