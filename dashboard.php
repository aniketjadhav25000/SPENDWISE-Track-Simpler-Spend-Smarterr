<?php
include("config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Total expense
$totalResult = mysqli_query(
    $conn,
    "SELECT SUM(amount) AS total FROM expenses WHERE user_id=$user_id"
);
$totalData = mysqli_fetch_assoc($totalResult);
$totalExpense = $totalData['total'] ?? 0;

// Total number of expenses
$countResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS count FROM expenses WHERE user_id=$user_id"
);
$countData = mysqli_fetch_assoc($countResult);
$totalCount = $countData['count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Expense Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100">

<!-- Navbar -->
<nav class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-indigo-600">
            Expense Tracker
        </h1>

        <a href="auth/logout.php"
           class="text-sm text-gray-600 hover:text-red-600 transition">
            Logout
        </a>
    </div>
</nav>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 py-8">

    <!-- Page Title -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-sm text-gray-500">
            Overview of your expense activity
        </p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <!-- Total Expense -->
        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
            <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600">
                <!-- Wallet Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 12h6m-3-3v6" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Expense</p>
                <p class="text-2xl font-bold text-gray-800">
                    ₹<?= number_format($totalExpense, 2); ?>
                </p>
            </div>
        </div>

        <!-- Total Entries -->
        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
            <div class="p-3 rounded-lg bg-green-100 text-green-600">
                <!-- List Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Entries</p>
                <p class="text-2xl font-bold text-gray-800">
                    <?= $totalCount; ?>
                </p>
            </div>
        </div>

        <!-- Average Expense -->
        <div class="bg-white rounded-xl shadow p-6 flex items-center gap-4">
            <div class="p-3 rounded-lg bg-yellow-100 text-yellow-600">
                <!-- Chart Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 3v18m4-14v14m4-10v10" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Avg Expense</p>
                <p class="text-2xl font-bold text-gray-800">
                    ₹<?= $totalCount > 0 ? number_format($totalExpense / $totalCount, 2) : "0.00"; ?>
                </p>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Quick Actions
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <a href="expenses/add.php"
               class="flex items-center justify-center gap-2 bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                <!-- Plus Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4" />
                </svg>
                Add Expense
            </a>

            <a href="expenses/list.php"
               class="flex items-center justify-center gap-2 bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-900 transition">
                <!-- Table Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                View Expenses
            </a>

        </div>
    </div>

</main>

</body>
</html>
