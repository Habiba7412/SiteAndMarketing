<?php
require_once __DIR__ . '/../includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `username` = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_id'] = $user['id'];
                
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Center | SiteAndMarketing IT Company</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0b1315',
                            darker: '#070c0e',
                            card: '#0e1a1d',
                            accent: '#38bdf8',
                            emerald: '#10b981',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styling triggers -->
    <style>
        .glow-box {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, rgba(0,0,0,0) 70%);
            filter: blur(40px);
        }
        .login-card {
            background: rgba(14, 26, 29, 0.45);
            backdrop-filter: blur(16px);
            border: 1px border;
            border-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="bg-brand-dark text-slate-100 font-sans min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Decorative Blobs -->
    <div class="glow-box w-96 h-96 -top-20 -left-20"></div>
    <div class="glow-box w-[450px] h-[450px] -bottom-20 -right-20" style="background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, rgba(0,0,0,0) 70%);"></div>

    <div class="w-full max-w-md px-6 py-12 relative z-10">
        <!-- Logo -->
        <div class="flex flex-col items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-accent to-emerald-400 flex items-center justify-center shadow-lg shadow-brand-accent/25">
                <i class="fa-solid fa-cubes text-brand-dark text-2xl font-bold"></i>
            </div>
            <h1 class="font-heading font-extrabold text-3xl tracking-tight text-white">
                SiteAndMarketing<span class="text-brand-accent">.</span> <span class="text-slate-400 text-lg font-medium">Control panel</span>
            </h1>
        </div>

        <!-- Login Card -->
        <div class="login-card p-8 rounded-3xl shadow-2xl">
            <h2 class="font-heading font-bold text-xl text-white mb-2 text-center">Sign In</h2>
            <p class="text-xs text-slate-400 mb-6 text-center">Enter your administrative credentials to continue.</p>

            <?php if (!empty($error)): ?>
                <div class="mb-4 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-sm shrink-0"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="flex flex-col gap-4">
                <div>
                    <label for="username" class="block text-xs font-semibold uppercase text-slate-400 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                            <i class="fa-solid fa-user-shield text-xs"></i>
                        </span>
                        <input type="text" name="username" id="username" required placeholder="admin" class="w-full bg-brand-dark/60 border border-slate-800 rounded-xl pl-9 pr-4 py-3 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-semibold uppercase text-slate-400">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                        <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full bg-brand-dark/60 border border-slate-800 rounded-xl pl-9 pr-4 py-3 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>
                </div>

                <button type="submit" class="mt-4 w-full py-3.5 rounded-xl font-heading font-bold text-center text-brand-dark bg-gradient-to-r from-brand-accent to-emerald-400 hover:shadow-lg hover:shadow-brand-accent/20 hover:scale-[1.01] transition-all text-sm">
                    Enter Dashboard
                </button>
            </form>

            <div class="mt-8 text-center text-xs text-slate-500 border-t border-slate-800/50 pt-4 flex justify-between items-center">
                <span>Default: admin / admin123</span>
                <a href="../index.php" class="text-brand-accent hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left-long text-[10px]"></i>
                    Back to site
                </a>
            </div>
        </div>
    </div>

</body>
</html>
