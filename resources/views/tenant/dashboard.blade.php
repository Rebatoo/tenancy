<!-- resources/views/tenant/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .page-title {
            margin-bottom: 30px;
            color: #333;
            text-align: center;
        }
        .welcome-container {
            max-width: 1200px;
            margin: 0 auto 20px;
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .welcome-text {
            margin: 0;
            font-size: 1.2rem;
        }
        .card {
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <p class="welcome-text">Welcome Tenant: <strong>{{ session('tenant_email') ?? 'Not logged in' }}</strong></p>
    </div>
    
    <div class="container">
        <h1 class="page-title">Tenant Dashboard</h1>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-people card-icon text-primary"></i>
                        <h5 class="card-title">Employee Management</h5>
                        <p class="card-text">Manage your employees, add new ones, or update existing records.</p>
                        <a href="{{ route('employees.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">Manage Employees</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-calendar-check card-icon text-success"></i>
                        <h5 class="card-title">Schedule</h5>
                        <p class="card-text">View and manage employee schedules and work shifts.</p>
                        <a href="#" class="btn btn-success">View Schedule</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-graph-up card-icon text-info"></i>
                        <h5 class="card-title">Reports</h5>
                        <p class="card-text">Generate reports on employee performance and attendance.</p>
                        <a href="#" class="btn btn-info">View Reports</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">No recent activity to display.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('employees.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus"></i> Add New Employee
                            </a>
                            <a href="#" class="btn btn-outline-success">
                                <i class="bi bi-calendar-plus"></i> Create Schedule
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="bi bi-file-earmark-text"></i> Generate Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
