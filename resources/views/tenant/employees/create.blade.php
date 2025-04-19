<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
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
            max-width: 800px;
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
    </style>
</head>
<body>
    <div class="welcome-container">
        <p class="welcome-text">Welcome Tenant: <strong>{{ session('tenant_email') ?? 'Not logged in' }}</strong></p>
    </div>
    
    <div class="container">
        <h1 class="page-title">Add New Employee</h1>
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <form action="{{ route('employees.store', ['tenant' => request()->route('tenant')]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="job_title" class="form-label">Job Title</label>
                    <input type="text" class="form-control" id="job_title" name="job_title" value="{{ old('job_title') }}">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="Washer" {{ old('role') == 'Washer' ? 'selected' : '' }}>Washer</option>
                        <option value="Ironer" {{ old('role') == 'Ironer' ? 'selected' : '' }}>Ironer</option>
                        <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Supervisor" {{ old('role') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Home Address</label>
                <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ old('birthdate') }}">
                </div>
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="employment_status" class="form-label">Employment Status</label>
                    <select class="form-select" id="employment_status" name="employment_status" required>
                        <option value="">Select Status</option>
                        <option value="Active" {{ old('employment_status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="On Leave" {{ old('employment_status') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="Resigned" {{ old('employment_status') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="shift" class="form-label">Work Shift</label>
                    <select class="form-select" id="shift" name="shift">
                        <option value="">Select Shift</option>
                        <option value="Morning" {{ old('shift') == 'Morning' ? 'selected' : '' }}>Morning</option>
                        <option value="Afternoon" {{ old('shift') == 'Afternoon' ? 'selected' : '' }}>Afternoon</option>
                        <option value="Night" {{ old('shift') == 'Night' ? 'selected' : '' }}>Night</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="photo" class="form-label">Profile Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    <div class="form-text">Upload a profile photo or ID image (JPG, PNG, GIF)</div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('employees.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-secondary me-md-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Employee</button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 