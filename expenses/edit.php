<?php
include("../config/db.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM expenses WHERE id=$id AND user_id=$user_id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: list.php");
    exit();
}

if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $amount = (float) $_POST['amount'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    mysqli_query($conn, "UPDATE expenses SET title='$title', amount=$amount, category='$category' WHERE id=$id AND user_id=$user_id");
    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Entry | SpendWise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#f1f5f9] text-slate-900 p-4 md:p-8 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-xl">
        <div class="flex items-center justify-between mb-8 px-4">
            <a href="list.php" class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition font-black text-[10px] uppercase tracking-widest">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
            </a>
            <h1 class="text-xl font-black text-slate-900 tracking-tighter italic">SPEND<span class="text-indigo-600">WISE</span></h1>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-200 p-8 md:p-12">
            <header class="mb-10 text-center md:text-left">
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Edit Record</h2>
                <p class="text-slate-500 mt-2 font-medium">Update the transaction specifics below.</p>
            </header>

            <form method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Title</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($data['title']) ?>"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-bold text-slate-700">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Amount (₹)</label>
                        <input type="number" step="0.01" name="amount" required value="<?= htmlspecialchars($data['amount']) ?>"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-black text-slate-900">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Category</label>
                        <input type="text" name="category" value="<?= htmlspecialchars($data['category']) ?>"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-bold text-slate-700">
                    </div>
                </div>

                <div class="pt-6 space-y-4">
                    <button type="submit" name="update" 
                            class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-100 active:scale-[0.98]">
                        Save Changes
                    </button>
                    <a href="list.php" class="block w-full text-center bg-slate-100 text-slate-500 py-5 rounded-2xl font-black hover:bg-slate-200 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>