<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

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
<html lang="en" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AeroCMS - Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
          colors: {
            brand: {
              50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
              500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
              accent: '#8b5cf6'
            }
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center relative overflow-hidden">
  
  <!-- Decorative background elements -->
  <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-brand-500/20 rounded-full blur-[100px] pointer-events-none"></div>
  <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-brand-accent/20 rounded-full blur-[100px] pointer-events-none"></div>

  <div class="w-full max-w-md px-6 relative z-10">
    <div class="text-center mb-10">
      <div class="w-16 h-16 bg-gradient-to-br from-brand-500 to-brand-accent rounded-2xl flex items-center justify-center text-white mx-auto shadow-xl shadow-brand-500/30 mb-6">
        <i class="fa-solid fa-cube text-2xl"></i>
      </div>
      <h1 class="text-3xl font-extrabold tracking-tight">Welcome back</h1>
      <p class="text-slate-500 mt-2 font-medium">Enter your credentials to access AeroCMS</p>
    </div>

    <div class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
      <?php if (!empty($error)): ?>
          <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 text-sm font-medium">
              <i class="fa-solid fa-triangle-exclamation"></i>
              <span><?php echo htmlspecialchars($error); ?></span>
          </div>
      <?php endif; ?>

      <form action="login.php" method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
              <i class="fa-solid fa-user text-sm"></i>
            </span>
            <input type="text" name="username" required placeholder="admin" 
                   class="w-full pl-11 pr-4 py-3 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
          </div>
        </div>

        <div>
          <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-semibold text-slate-700">Password</label>
            <a href="forgot-password.php" class="text-sm font-semibold text-brand-600 hover:text-brand-700 hover:underline">Forgot password?</a>
          </div>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
              <i class="fa-solid fa-lock text-sm"></i>
            </span>
            <input type="password" name="password" required placeholder="••••••••" 
                   class="w-full pl-11 pr-4 py-3 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
          </div>
        </div>

        <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-2xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-0.5 mt-2">
          Sign In
        </button>
      </form>
    </div>
  </div>

</body>
</html>
