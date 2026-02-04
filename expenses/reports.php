<?php
// 1. Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../config/db.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch User Identity for Sidebar/Mobile Header
$userResult = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$userData = mysqli_fetch_assoc($userResult);
$currentUserName = $userData['username'] ?? $userData['name'] ?? 'User';
$firstLetter = substr($currentUserName, 0, 1);

// 3. AUTO-DETECT COLUMN NAME
$checkCols = mysqli_query($conn, "SHOW COLUMNS FROM expenses");
$dateCol = "expense_date"; 
while($col = mysqli_fetch_assoc($checkCols)) {
    if(in_array($col['Field'], ['expense_date', 'date', 'created_at'])) {
        $dateCol = $col['Field'];
        break;
    }
}

// 4. Get Category Breakdown
$catQuery = "SELECT category, SUM(amount) as total FROM expenses WHERE user_id=$user_id GROUP BY category";
$catResult = mysqli_query($conn, $catQuery);
$categories = [];
$categoryTotals = [];

while($row = mysqli_fetch_assoc($catResult)) {
    $categories[] = $row['category'];
    $categoryTotals[] = $row['total'];
}

// 5. Get Highest Expense
$highQuery = "SELECT title, amount, category FROM expenses WHERE user_id=$user_id ORDER BY amount DESC LIMIT 1";
$highResult = mysqli_query($conn, $highQuery);
$highest = mysqli_fetch_assoc($highResult);

// 6. Monthly total
$monthQuery = "SELECT SUM(amount) as total FROM expenses WHERE user_id=$user_id AND MONTH($dateCol) = MONTH(CURRENT_DATE()) AND YEAR($dateCol) = YEAR(CURRENT_DATE())";
$monthResult = mysqli_query($conn, $monthQuery);
$monthData = mysqli_fetch_assoc($monthResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Detailed Reports | SpendWise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .chart-container { position: relative; height: 300px; width: 100%; }
        @media (max-width: 768px) { .chart-container { height: 250px; } }
    </style>
</head>
<body class="bg-[#f1f5f9] text-slate-900 pb-24 lg:pb-0">

<div class="flex min-h-screen">
    <aside class="w-64 bg-slate-900 text-slate-400 hidden lg:flex flex-col fixed h-full z-20">
        <div class="p-8">
            <h1 class="text-2xl font-black text-white tracking-tighter italic">SPEND<span class="text-indigo-500">WISE</span></h1>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <a href="../dashboard.php" class="flex items-center gap-3 hover:bg-slate-800 text-slate-400 px-4 py-3 rounded-xl font-bold transition">
                <i data-lucide="layout-grid" class="w-5 h-5"></i> Overview
            </a>
            <a href="list.php" class="flex items-center gap-3 hover:bg-slate-800 text-slate-400 px-4 py-3 rounded-xl font-bold transition">
                <i data-lucide="list" class="w-5 h-5"></i> History
            </a>
            <a href="reports.php" class="flex items-center gap-3 bg-indigo-600 text-white px-4 py-3 rounded-xl font-bold transition shadow-lg shadow-indigo-900/20">
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

    <main class="flex-1 lg:ml-64 p-4 md:p-8 lg:p-12">
        <div class="max-w-6xl mx-auto">
            
            <header class="flex lg:hidden items-center justify-between mb-8 px-2">
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tighter italic">SPEND<span class="text-indigo-600">WISE</span></h1>
                  
                </div>
                <a href="../profile.php" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-sm text-indigo-600 font-black">
                    <?= $firstLetter ?>
                </a>
            </header>

            <header class="hidden lg:block mb-10">
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Spending Analysis</h2>
                <p class="text-slate-500 mt-2 font-medium">Deep dive into your financial habits and category distributions.</p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-8">
                <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="p-3 md:p-4 bg-orange-50 text-orange-600 rounded-2xl shrink-0">
                        <i data-lucide="trending-up" class="w-6 h-6 md:w-8 md:h-8"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Top Expense</p>
                        <h4 class="text-xl md:text-2xl font-black text-slate-900">₹<?= number_format($highest['amount'] ?? 0) ?></h4>
                        <p class="text-[11px] text-slate-500 font-bold truncate italic"><?= htmlspecialchars($highest['title'] ?? 'No Records') ?></p>
                    </div>
                </div>

                <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="p-3 md:p-4 bg-indigo-50 text-indigo-600 rounded-2xl shrink-0">
                        <i data-lucide="calendar" class="w-6 h-6 md:w-8 md:h-8"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Month Total</p>
                        <h4 class="text-xl md:text-2xl font-black text-slate-900">₹<?= number_format($monthData['total'] ?? 0) ?></h4>
                        <p class="text-[11px] text-slate-500 font-bold italic">Current Cycle</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                    <h3 class="font-black text-slate-800 text-lg mb-6 flex items-center gap-2">
                        <i data-lucide="pie-chart" class="w-5 h-5 text-indigo-500"></i> Distribution
                    </h3>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                    <h3 class="font-black text-slate-800 text-lg mb-6">Cluster Breakdown</h3>
                    <div class="space-y-5">
                        <?php if(!empty($categories)): ?>
                            <?php foreach($categories as $index => $cat): 
                                $totalAll = array_sum($categoryTotals);
                                $percentage = ($totalAll > 0) ? ($categoryTotals[$index] / $totalAll) * 100 : 0;
                            ?>
                            <div>
                                <div class="flex justify-between text-xs mb-2">
                                    <span class="text-slate-600 font-bold"><?= htmlspecialchars($cat) ?></span>
                                    <span class="font-black text-slate-900">₹<?= number_format($categoryTotals[$index]) ?></span>
                                </div>
                                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-10">
                                <i data-lucide="database" class="w-10 h-10 text-slate-200 mx-auto mb-3"></i>
                                <p class="text-slate-400 italic text-sm font-bold">No data to analyze.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-lg border-t border-slate-200 px-6 py-3 flex justify-between items-center z-50">
    <a href="../dashboard.php" class="flex flex-col items-center gap-1 text-slate-400">
        <i data-lucide="layout-grid" class="w-6 h-6"></i>
        <span class="text-[10px] font-bold">Home</span>
    </a>
    <a href="list.php" class="flex flex-col items-center gap-1 text-slate-400">
        <i data-lucide="list" class="w-6 h-6"></i>
        <span class="text-[10px] font-bold">History</span>
    </a>
    <a href="reports.php" class="flex flex-col items-center gap-1 text-indigo-600">
        <div class="bg-indigo-50 p-2 rounded-xl">
            <i data-lucide="pie-chart" class="w-6 h-6"></i>
        </div>
        <span class="text-[10px] font-bold">Reports</span>
    </a>
    <a href="../auth/logout.php" onclick="return confirm('Logout?')" class="flex flex-col items-center gap-1 text-red-400">
        <i data-lucide="log-out" class="w-6 h-6"></i>
        <span class="text-[10px] font-bold">Logout</span>
    </a>
</div>

<script>
    lucide.createIcons();

    const ctx = document.getElementById('categoryChart').getContext('2d');
    const categories = <?= json_encode($categories) ?>;
    
    if(categories.length > 0) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: <?= json_encode($categoryTotals) ?>,
                    backgroundColor: ['#4f46e5', '#6366f1', '#818cf8', '#a5b4fc', '#c7d2fe', '#e0e7ff'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 15
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            usePointStyle: true, 
                            padding: 20,
                            font: { family: 'Inter', weight: 'bold', size: 11 }
                        } 
                    }
                }
            }
        });
    }
</script>
</body>
</html>