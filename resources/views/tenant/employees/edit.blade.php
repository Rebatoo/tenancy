<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow px-5 py-6 sm:px-6">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Edit Employee</h2>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tenant.employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" required
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Job Title</label>
                            <input type="text" name="role" id="role" value="{{ old('role', $employee->role) }}" required
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}" required
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Address -->
                        <div class="sm:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" required
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('address', $employee->address) }}</textarea>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="gender"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Birthdate -->
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                            <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', $employee->birthdate?->format('Y-m-d')) }}"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $employee->start_date->format('Y-m-d')) }}" required
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="Active" {{ old('status', $employee->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="On Leave" {{ old('status', $employee->status) == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                <option value="Resigned" {{ old('status', $employee->status) == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                            </select>
                        </div>

                        <!-- Shift -->
                        <div>
                            <label for="shift" class="block text-sm font-medium text-gray-700">Shift</label>
                            <select name="shift" id="shift" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="Morning" {{ old('shift', $employee->shift) == 'Morning' ? 'selected' : '' }}>Morning</option>
                                <option value="Evening" {{ old('shift', $employee->shift) == 'Evening' ? 'selected' : '' }}>Evening</option>
                                <option value="Night" {{ old('shift', $employee->shift) == 'Night' ? 'selected' : '' }}>Night</option>
                                <option value="Custom" {{ old('shift', $employee->shift) == 'Custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        <!-- Photo -->
                        <div class="sm:col-span-2">
                            <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                            @if($employee->photo)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($employee->photo) }}" alt="{{ $employee->name }}" class="h-20 w-20 rounded-full">
                                </div>
                            @endif
                            <input type="file" name="photo" id="photo" accept="image/*"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('tenant.employees.index') }}" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 