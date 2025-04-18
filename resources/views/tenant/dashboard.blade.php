<!-- resources/views/tenant/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard - Employee Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold">Employee Management</h1>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Add Employee Button -->
            <div class="mb-4">
                <a href="{{ route('tenant.employees.create', ['tenant' => $tenant->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Employee
                </a>
            </div>

            <!-- Employees Table -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($employees as $employee)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($employee->photo)
                                        <img src="{{ Storage::url($employee->photo) }}" alt="{{ $employee->name }}" class="h-10 w-10 rounded-full">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 text-sm">{{ substr($employee->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $employee->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $employee->role }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $employee->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $employee->status === 'Active' ? 'bg-green-100 text-green-800' : 
                                           ($employee->status === 'On Leave' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ $employee->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tenant.employees.edit', ['tenant' => $tenant->id, 'employee' => $employee]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('tenant.employees.destroy', ['tenant' => $tenant->id, 'employee' => $employee]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $employees->links() }}
            </div>
        </main>
    </div>
</body>
</html>
