<?php
include("../config/db.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$success = false;
$error = "";

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkQuery = "SELECT id FROM users WHERE email='$email' LIMIT 1";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $error = "This email is already registered.";
    } else {
        $insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $insertQuery)) { $success = true; } 
        else { $error = "Something went wrong."; }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join SpendWise | Create Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-[440px]">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-black text-slate-900 tracking-tighter italic">
                SPEND<span class="text-indigo-600">WISE</span>
            </h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Personal Finance Architect</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-200 p-7 md:p-9">
            <header class="mb-6 text-center sm:text-left">
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Create Account</h2>
                <p class="text-slate-500 text-sm font-medium mt-0.5">Start your journey to financial clarity.</p>
            </header>

            <?php if ($success): ?>
                <div class="mb-5 p-3 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-between text-emerald-700 text-xs font-bold">
                    <div class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4"></i> Done!</div>
                    <a href="login.php" class="text-indigo-600 underline">Login now</a>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="mb-5 p-3 rounded-2xl bg-red-50 border border-red-100 flex items-center gap-2 text-red-600 text-xs font-bold">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </span>
                        <input type="text" name="name" required placeholder="John Doe"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" name="email" required placeholder="name@example.com"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-6 py-3.5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition font-semibold text-slate-700 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" name="register" 
                        class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-100 active:scale-[0.98] flex items-center justify-center gap-2">
                        Create Account
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500 font-medium">
                    Already a member? 
                    <a href="login.php" class="text-indigo-600 font-black hover:underline ml-1">Sign In</a>
                </p>
            </div>
        </div>
        <div class="mt-8 flex items-center justify-center gap-2 text-slate-400">
            <i data-lucide="shield-check" class="w-4 h-4"></i>
            <span class="text-[10px] font-black uppercase tracking-[0.1em]">Secured by SpendWise SSL</span>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>