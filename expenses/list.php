<?php
include("../config/db.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- NEW: Fetch User Identity for Profile Button ---
$userResult = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$userData = mysqli_fetch_assoc($userResult);
$currentUserName = $userData['username'] ?? $userData['name'] ?? 'User';
$firstLetter = substr($currentUserName, 0, 1);

// Fetch transactions
$result = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id=$user_id ORDER BY expense_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction History | SpendWise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 pb-24 lg:pb-0">

<div class="flex min-h-screen">
    <aside class="w-64 bg-slate-900 text-slate-400 hidden lg:flex flex-col fixed h-full z-20">
        <div class="p-8">
            <h1 class="text-2xl font-black text-white tracking-tighter italic">SPEND<span class="text-indigo-500">WISE</span></h1>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <a href="../dashboard.php" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-xl font-bold transition">
                <i data-lucide="layout-grid" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="list.php" class="flex items-center gap-3 bg-indigo-600 text-white px-4 py-3 rounded-xl font-bold transition shadow-lg shadow-indigo-900/20">
                <i data-lucide="list" class="w-5 h-5"></i> History
            </a>
            <a href="reports.php" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-xl font-bold transition">
                <i data-lucide="pie-chart" class="w-5 h-5"></i> Detailed Reports
            </a>
        </nav>
        
        <div class="p-4 border-t border-slate-800">
            <a href="../auth/logout.php" onclick="return confirm('Logout?')" class="flex items-center gap-3 text-red-400 hover:bg-red-500/10 hover:text-red-300 px-4 py-3 rounded-xl transition font-bold">
                <i data-lucide="log-out" class="w-5 h-5"></i> Logout
            </a>
        </div>

        <a href="../profile.php" class="p-6 border-t border-slate-800/50 hover:bg-slate-800 transition group block">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-slate-800 rounded-xl flex items-center justify-center text-white text-xs font-black group-hover:bg-indigo-600 transition border border-slate-700">
                    <?= $firstLetter ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] uppercase tracking-widest font-black text-slate-500 group-hover:text-slate-400 transition">User Profile</p>
                    <p class="text-white font-bold text-sm truncate"><?= htmlspecialchars($currentUserName) ?></p>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-600 group-hover:text-white transition ml-auto"></i>
            </div>
        </a>
    </aside>

    <main class="flex-1 lg:ml-64">
        <header class="h-16 bg-white border-b border-slate-200 flex lg:hidden items-center justify-between px-6 sticky top-0 z-30">
            <h1 class="text-xl font-black text-slate-900 tracking-tighter italic">SPEND<span class="text-indigo-600">WISE</span></h1>
            <div class="flex items-center gap-3">
                <a href="../profile.php" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-xs border border-slate-200">
                    <?= $firstLetter ?>
                </a>
                <a href="add.php" class="p-2 bg-indigo-600 text-white rounded-lg shadow-md shadow-indigo-100"><i data-lucide="plus" class="w-5 h-5"></i></a>
            </div>
        </header>

        <div class="p-4 md:p-8 max-w-5xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">History</h2>
                    <p class="text-slate-500 text-sm font-medium">Manage your spending records</p>
                </div>
                <a href="add.php" class="hidden md:flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl font-black hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    <i data-lucide="plus" class="w-5 h-5"></i> New Entry
                </a>
            </div>

            <div class="bg-white lg:rounded-[2rem] rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-8 py-4">Transaction</th>
                                <th class="px-8 py-4">Category</th>
                                <th class="px-8 py-4 text-right">Amount</th>
                                <th class="px-8 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500"><i data-lucide="receipt" class="w-5 h-5"></i></div>
                                                <div>
                                                    <p class="font-bold text-slate-800"><?= htmlspecialchars($row['title']) ?></p>
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase"><?= date("M d, Y", strtotime($row['expense_date'])) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-indigo-50 text-indigo-600 border border-indigo-100">
                                                <?= htmlspecialchars($row['category']) ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-right font-black text-slate-900">₹<?= number_format($row['amount'], 2) ?></td>
                                        <td class="px-8 py-5">
                                            <div class="flex justify-center gap-3">
                                                <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-500 hover:scale-110 transition-transform"><i data-lucide="edit-3" class="w-5 h-5"></i></a>
                                                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete?')" class="text-red-500 hover:scale-110 transition-transform"><i data-lucide="trash-2" class="w-5 h-5"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php else: echo "<tr><td colspan='4' class='py-20 text-center text-slate-400 font-bold uppercase text-xs tracking-widest'>No records found</td></tr>"; endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden divide-y divide-slate-100">
                    <?php 
                    mysqli_data_seek($result, 0);
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="p-5 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <i data-lucide="receipt" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-900 leading-tight"><?= htmlspecialchars($row['title']) ?></p>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter mt-0.5">
                                        <?= date("M d", strtotime($row['expense_date'])) ?> • <?= htmlspecialchars($row['category']) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex flex-col items-end gap-2">
                                <p class="font-black text-slate-900">₹<?= number_format($row['amount']) ?></p>
                                <div class="flex gap-3">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-500"><i data-lucide="edit-3" class="w-4 h-4"></i></a>
                                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete?')" class="text-red-500"><i data-lucide="trash-2" class="w-4 h-4"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-slate-200 px-6 py-3 flex justify-between items-center z-50">
    <a href="../dashboard.php" class="flex flex-col items-center gap-1 text-slate-400">
        <i data-lucide="layout-grid" class="w-6 h-6"></i>
        <span class="text-[10px] font-bold tracking-tight">Home</span>
    </a>
    <a href="list.php" class="flex flex-col items-center gap-1 text-indigo-600">
        <div class="bg-indigo-50 p-2 rounded-xl">
            <i data-lucide="list" class="w-6 h-6"></i>
        </div>
        <span class="text-[10px] font-bold tracking-tight">History</span>
    </a>
    <a href="reports.php" class="flex flex-col items-center gap-1 text-slate-400">
        <i data-lucide="pie-chart" class="w-6 h-6"></i>
        <span class="text-[10px] font-bold tracking-tight">Reports</span>
    </a>
    <a href="../auth/logout.php" onclick="return confirm('Logout?')" class="flex flex-col items-center gap-1 text-red-400">
        <i data-lucide="log-out" class="w-6 h-6"></i>
        <span class="text-[10px] font-bold tracking-tight">Logout</span>
    </a>
</div>

<script>lucide.createIcons();</script>
</body>
</html>