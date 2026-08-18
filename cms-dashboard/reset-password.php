<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';
$isValidToken = false;
$userId = null;

if (!empty($token)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `reset_token` = ? AND `reset_expires` > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            $isValidToken = true;
            $userId = $user['id'];
        } else {
            $error = "Invalid or expired reset link. Please request a new one.";
        }
    } catch (PDOException $e) {
        $error = "System error. Please try again later.";
    }
} else {
    $error = "No reset token provided.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isValidToken) {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (empty($password) || empty($confirm)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        try {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE `users` SET `password` = ?, `reset_token` = NULL, `reset_expires` = NULL WHERE `id` = ?");
            $stmt->execute([$hashed, $userId]);
            
            $success = "Your password has been successfully reset. You can now login.";
            $isValidToken = false; // Hide the form
        } catch (PDOException $e) {
            $error = "Error saving new password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - AeroCMS</title>
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
  
  <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-brand-500/20 rounded-full blur-[100px] pointer-events-none"></div>
  <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-brand-accent/20 rounded-full blur-[100px] pointer-events-none"></div>

  <div class="w-full max-w-md px-6 relative z-10">
    <div class="text-center mb-10">
      <div class="w-16 h-16 bg-gradient-to-br from-brand-500 to-brand-accent rounded-2xl flex items-center justify-center text-white mx-auto shadow-xl shadow-brand-500/30 mb-6">
        <i class="fa-solid fa-lock text-2xl"></i>
      </div>
      <h1 class="text-3xl font-extrabold tracking-tight">Create New Password</h1>
      <p class="text-slate-500 mt-2 font-medium">Enter a strong password for your account</p>
    </div>

    <div class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
      <?php if (!empty($error)): ?>
          <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 text-sm font-medium">
              <i class="fa-solid fa-triangle-exclamation"></i>
              <span><?php echo htmlspecialchars($error); ?></span>
          </div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
          <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 text-sm font-medium">
              <i class="fa-solid fa-check-circle"></i>
              <span><?php echo htmlspecialchars($success); ?></span>
          </div>
          <a href="login.php" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-2xl shadow-lg shadow-brand-500/30 transition-all text-center block">
              Go to Login
          </a>
      <?php endif; ?>

      <?php if ($isValidToken): ?>
      <form action="reset-password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
              <i class="fa-solid fa-lock text-sm"></i>
            </span>
            <input type="password" name="password" required placeholder="••••••••" minlength="8"
                   class="w-full pl-11 pr-4 py-3 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
              <i class="fa-solid fa-lock text-sm"></i>
            </span>
            <input type="password" name="confirm_password" required placeholder="••••••••" minlength="8"
                   class="w-full pl-11 pr-4 py-3 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
          </div>
        </div>

        <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-2xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-0.5 mt-2">
          Save Password
        </button>
      </form>
      <?php endif; ?>
      
      <?php if (!$isValidToken && empty($success)): ?>
          <div class="mt-6 text-center">
            <a href="forgot-password.php" class="text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                Request new reset link
            </a>
          </div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
