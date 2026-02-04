<?php
include("config/db.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch User Info
$userQuery = mysqli_query($conn, "SELECT username, email FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($userQuery);

// Fetch Stats for Profile
$statsQuery = mysqli_query($conn, "SELECT COUNT(*) as count, SUM(amount) as total FROM expenses WHERE user_id=$user_id");
$stats = mysqli_fetch_assoc($statsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | SpendWise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#f8fafc] text-slate-900">

<div class="flex min-h-screen">
    <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col fixed h-full">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-indigo-600">SpendWise</h1>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 text-slate-600 hover:bg-slate-50 px-4 py-3 rounded-xl transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="expenses/reports.php" class="flex items-center gap-3 text-slate-600 hover:bg-slate-50 px-4 py-3 rounded-xl transition">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i> Detailed Reports
            </a>
            <a href="expenses/list.php" class="flex items-center gap-3 text-slate-600 hover:bg-slate-50 px-4 py-3 rounded-xl transition">
                <i data-lucide="list" class="w-5 h-5"></i> History
            </a>
        </nav>
    </aside>

    <main class="flex-1 md:ml-64 p-8">
        <div class="max-w-4xl mx-auto">
            <header class="mb-10">
                <a href="dashboard.php" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 transition mb-4">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Dashboard
                </a>
                <h2 class="text-3xl font-bold text-slate-900">Profile Settings</h2>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 text-center shadow-sm">
                        <div class="w-24 h-24 bg-indigo-600 rounded-3xl mx-auto flex items-center justify-center text-white text-3xl font-bold shadow-xl shadow-indigo-100 mb-4 uppercase">
                            <?= substr($user['username'], 0, 1) ?>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($user['username']) ?></h3>
                        <p class="text-slate-500 text-sm mb-6"><?= htmlspecialchars($user['email']) ?></p>
                        
                        <div class="pt-6 border-t border-slate-100 flex justify-around">
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Entries</p>
                                <p class="text-lg font-bold text-slate-800"><?= $stats['count'] ?? 0 ?></p>
                            </div>
                            <div class="border-l border-slate-100"></div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Rank</p>
                                <p class="text-lg font-bold text-indigo-600">Saver</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i data-lucide="user-check" class="w-5 h-5 text-indigo-500"></i> Personal Information
                        </h4>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Username</label>
                                    <div class="p-3 bg-slate-50 rounded-xl text-slate-700 font-medium"><?= htmlspecialchars($user['username']) ?></div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Currency</label>
                                    <div class="p-3 bg-slate-50 rounded-xl text-slate-700 font-medium">INR (₹)</div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Email Address</label>
                                <div class="p-3 bg-slate-50 rounded-xl text-slate-700 font-medium"><?= htmlspecialchars($user['email']) ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-500"></i> Account Security
                        </h4>
                        <p class="text-sm text-slate-500 mb-6">Your account is secured with standard encryption.</p>
                        <button class="text-sm font-bold text-indigo-600 hover:underline">Change Password</button>
                    </div>

                    <a href="auth/logout.php" class="flex items-center justify-center gap-2 w-full p-4 bg-red-50 text-red-600 rounded-2xl font-bold hover:bg-red-100 transition">
                        <i data-lucide="log-out" class="w-5 h-5"></i> Log Out from Device
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<script>lucide.createIcons();</script>
</body>
</html>