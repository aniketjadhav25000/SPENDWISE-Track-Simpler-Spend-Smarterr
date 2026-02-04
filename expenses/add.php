<?php
// 1. Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 2. Database Connection
$root = dirname(__DIR__);
include($root . "/config/db.php");

// 3. Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 4. Date Column Auto-Detection
$checkCols = mysqli_query($conn, "SHOW COLUMNS FROM expenses");
$dateCol = "expense_date"; 
while($col = mysqli_fetch_assoc($checkCols)) {
    if(in_array($col['Field'], ['expense_date', 'date', 'created_at'])) {
        $dateCol = $col['Field'];
        break;
    }
}

$error_msg = "";
if (isset($_POST['add'])) {
    $title    = mysqli_real_escape_string($conn, $_POST['title']);
    $amount   = mysqli_real_escape_string($conn, $_POST['amount']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $date     = mysqli_real_escape_string($conn, $_POST['date']);

    $query = "INSERT INTO expenses (user_id, title, amount, category, $dateCol) 
              VALUES ('$user_id', '$title', '$amount', '$category', '$date')";

    if (mysqli_query($conn, $query)) {
        header("Location: ../dashboard.php");
        exit();
    } else {
        $error_msg = "Database Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Entry | SpendWise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Smooth transition for inputs on mobile tap */
        input, select { font-size: 16px !important; } 
    </style>
</head>
<body class="bg-[#f1f5f9] text-slate-900 min-h-screen flex flex-col items-center justify-center p-4 md:p-8">

    <div class="w-full sm:max-w-xl">
        
        <header class="mb-6 md:mb-8 text-center">
            <a href="../dashboard.php" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition mb-4 md:mb-6 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-200">
                <i data-lucide="chevron-left" class="w-4 h-4"></i> Back to Dashboard
            </a>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Add Transaction</h2>
            <p class="text-slate-500 mt-2 text-sm md:text-base font-medium italic">Documenting your financial flow...</p>
        </header>

        <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] border border-slate-200 shadow-2xl overflow-hidden">
            <form method="POST" class="p-6 md:p-12 space-y-6 md:space-y-8">
                
                <?php if(!empty($error_msg)): ?>
                    <div class="p-4 bg-red-50 text-red-700 rounded-2xl text-sm border border-red-100 font-bold italic">
                        <i data-lucide="alert-circle" class="w-4 h-4 inline mr-2"></i> <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>

                <div class="space-y-5 md:space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 md:mb-3 ml-1">Label</label>
                        <input type="text" name="title" required placeholder="e.g. Weekly Groceries" 
                            class="w-full bg-slate-50 border border-slate-200 px-4 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition text-slate-700 font-bold">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 md:mb-3 ml-1">Value (₹)</label>
                            <input type="number" name="amount" step="0.01" required placeholder="0.00" 
                                class="w-full bg-slate-50 border border-slate-200 px-4 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition text-slate-900 font-black text-lg">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 md:mb-3 ml-1">Timestamp</label>
                            <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>"
                                class="w-full bg-slate-50 border border-slate-200 px-4 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition text-slate-600 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 md:mb-3 ml-1">Cluster Category</label>
                        <div class="relative">
                            <select name="category" required class="w-full bg-slate-50 border border-slate-200 px-4 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 transition text-slate-600 font-bold appearance-none cursor-pointer">
                                <option value="Food">Food & Dining</option>
                                <option value="Transport">Transport</option>
                                <option value="Shopping">Shopping</option>
                                <option value="Bills">Bills & Utilities</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Other">Miscellaneous</option>
                            </select>
                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" name="add" 
                    class="w-full bg-slate-900 text-white py-4 md:py-5 rounded-xl md:rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl flex items-center justify-center gap-2 active:scale-[0.98]">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i> Confirm Transaction
                </button>
            </form>
        </div>

        <p class="text-center mt-6 md:mt-8 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Secure Financial Portal</p>
    </div>

<script>
    lucide.createIcons();
</script>
</body>
</html>