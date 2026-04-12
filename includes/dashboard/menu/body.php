<div class="p-8 w-full bg-gray-50 min-h-screen">

    <!-- Page Title -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Menu Management</h1>
            <p class="text-gray-500 mt-1">Manage your food and beverage items</p>
        </div>

        <button
            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Menu
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Total Menu Items</p>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">120</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Available Items</p>
            <h2 class="text-2xl font-bold text-green-600 mt-2">95</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Out of Stock</p>
            <h2 class="text-2xl font-bold text-red-500 mt-2">25</h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Categories</p>
            <h2 class="text-2xl font-bold text-blue-600 mt-2">8</h2>
        </div>
    </div>

    <!-- Search + Filters -->
    <div class="bg-white p-5 rounded-xl shadow mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <!-- Search -->
        <div class="flex items-center gap-3 w-full md:w-1/2">
            <input type="text" placeholder="Search menu..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-400 outline-none">
        </div>

        <!-- Filters -->
        <div class="flex gap-3">
            <select class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">All Categories</option>
                <option value="food">Food</option>
                <option value="drink">Drink</option>
                <option value="dessert">Dessert</option>
            </select>

            <select class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>
    </div>

    <!-- Menu Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                <tr>
                    <th class="p-4">Image</th>
                    <th class="p-4">Menu Name</th>
                    <th class="p-4">Category</th>
                    <th class="p-4">Price</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>

            <tbody>
                <!-- Row Example -->
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-4">
                        <img src="https://via.placeholder.com/50"
                            class="w-12 h-12 rounded-lg object-cover">
                    </td>

                    <td class="p-4 font-semibold text-gray-800">Chicken Chop</td>
                    <td class="p-4 text-gray-600">Food</td>
                    <td class="p-4 text-gray-600">RM 12.00</td>

                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                            Available
                        </span>
                    </td>

                    <td class="p-4 flex gap-2">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg text-sm">
                            Edit
                        </button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg text-sm">
                            Delete
                        </button>
                    </td>
                </tr>

                <!-- Row 2 -->
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-4">
                        <img src="https://via.placeholder.com/50"
                            class="w-12 h-12 rounded-lg object-cover">
                    </td>

                    <td class="p-4 font-semibold text-gray-800">Ice Lemon Tea</td>
                    <td class="p-4 text-gray-600">Drink</td>
                    <td class="p-4 text-gray-600">RM 5.00</td>

                    <td class="p-4">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                            Unavailable
                        </span>
                    </td>

                    <td class="p-4 flex gap-2">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg text-sm">
                            Edit
                        </button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg text-sm">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>