<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $updateStmt = $pdo->prepare("UPDATE `users` SET `reset_token` = ?, `reset_expires` = ? WHERE `id` = ?");
                $updateStmt->execute([$token, $expires, $user['id']]);
                
                $resetLink = rtrim($webSettings['website_url'] ?? 'https://siteandmarketing.com', '/') . "/cms-dashboard/reset-password.php?token=" . $token;
                
                $subject = "Password Reset Request - AeroCMS";
                $body = "
                <div style='font-family: sans-serif; max-w: 600px; margin: 0 auto; background: #0b0f19; color: #fff; padding: 40px; border-radius: 20px;'>
                    <h2 style='color: #fff;'>Reset Your Password</h2>
                    <p style='color: #cbd5e1; font-size: 16px; line-height: 1.6;'>You requested a password reset for your AeroCMS admin account. Click the button below to set a new password. This link will expire in 1 hour.</p>
                    <div style='text-align: center; margin: 40px 0;'>
                        <a href='{$resetLink}' style='background: #6366f1; color: #fff; padding: 15px 30px; text-decoration: none; border-radius: 10px; font-weight: bold; display: inline-block;'>Reset Password</a>
                    </div>
                    <p style='color: #64748b; font-size: 12px;'>If you did not request this, please ignore this email.</p>
                </div>";
                
                sendDynamicEmail($pdo, $email, $subject, $body);
            }
            // Always show success to prevent email enumeration
            $success = "If your email is registered, a password reset link has been sent.";
        } catch (PDOException $e) {
            $error = "System error. Please try again later.";
        }
    } else {
        $error = "Please enter a valid email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - AeroCMS</title>
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
        <i class="fa-solid fa-key text-2xl"></i>
      </div>
      <h1 class="text-3xl font-extrabold tracking-tight">Forgot Password?</h1>
      <p class="text-slate-500 mt-2 font-medium">No worries, we'll send you reset instructions.</p>
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
      <?php endif; ?>

      <form action="forgot-password.php" method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
              <i class="fa-regular fa-envelope text-sm"></i>
            </span>
            <input type="email" name="email" required placeholder="admin@siteandmarketing.com" 
                   class="w-full pl-11 pr-4 py-3 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
          </div>
        </div>

        <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-2xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-0.5 mt-2">
          Reset Password
        </button>
      </form>
      
      <div class="mt-6 text-center">
        <a href="login.php" class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors flex items-center justify-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to Login
        </a>
      </div>
    </div>
  </div>

</body>
</html>
