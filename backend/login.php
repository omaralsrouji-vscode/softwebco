<?php
require_once '../config.php';
if (!empty($_SESSION['logged_in'])) { header('Location: admin.php'); exit(); }
$versionFile = __DIR__ . '/../VERSION';
$swcVersion = is_file($versionFile) ? trim((string)file_get_contents($versionFile)) : '2.1.3';
$error = $_GET['error'] ?? '';
$errorText = $error === 'empty' ? 'Enter your username and password.' : ($error === 'invalid' ? 'Username or password is incorrect, or the account is unavailable.' : '');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#0a2530">
    <title>Softwebco Admin Login</title>
    <link rel="icon" href="../assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?php echo rawurlencode($swcVersion); ?>">
    <script defer src="../assets/js/icons.js?v=<?php echo rawurlencode($swcVersion); ?>"></script>
</head>
<body>
<main class="swc-login-page">
    <section class="swc-login-brand">
        <div class="swc-login-logo"><span><img src="../assets/images/Logo.png" alt="Softwebco"></span><strong>Softwebco Admin</strong></div>
        <div class="swc-login-hero">
            <span>Simple content management</span>
            <h1>Your website, without the clutter.</h1>
            <p>A focused admin workspace for the only things you need to manage: users, blogs and blog categories.</p>
            <div class="swc-login-points"><span>Dashboard</span><span>Users</span><span>Blogs</span><span>Categories</span></div>
        </div>
        <div class="swc-login-foot">SOFTWEBCO · v<?php echo htmlspecialchars($swcVersion); ?></div>
    </section>
    <section class="swc-login-side">
        <div class="swc-login-card">
            <div class="swc-login-card-badge"><i class="bi bi-shield-lock"></i></div>
            <h2>Welcome back</h2>
            <p>Sign in to manage Softwebco website content.</p>
            <?php if ($errorText): ?><div class="swc-alert swc-alert-error"><i class="bi bi-exclamation-circle"></i><span><?php echo htmlspecialchars($errorText); ?></span></div><?php endif; ?>
            <form action="auth.php" method="post">
                <label class="swc-login-field"><span class="swc-label">Username</span><span class="swc-login-input"><i class="bi bi-person"></i><input name="username" required autocomplete="username" autofocus placeholder="Your username"></span></label>
                <label class="swc-login-field"><span class="swc-label">Password</span><span class="swc-login-input"><i class="bi bi-lock"></i><input id="swcLoginPassword" type="password" name="password" required autocomplete="current-password" placeholder="Your password"><button class="swc-password-toggle" type="button" id="swcPasswordToggle" aria-label="Show password"><i class="bi bi-eye"></i></button></span></label>
                <button class="swc-login-submit" type="submit">Sign in <i class="bi bi-arrow-right" style="margin-left:6px"></i></button>
            </form>
            <a class="swc-login-back" href="../index.php"><i class="bi bi-arrow-left" style="margin-right:6px"></i> Back to website</a>
        </div>
    </section>
</main>
<script>
(function(){
  const button=document.getElementById('swcPasswordToggle');
  const input=document.getElementById('swcLoginPassword');
  if(!button||!input)return;
  button.addEventListener('click',function(){
    const show=input.type==='password';
    input.type=show?'text':'password';
    button.setAttribute('aria-label',show?'Hide password':'Show password');
    const icon=button.querySelector('.swc-svg-icon, i');
    if(window.SoftwebcoIcons&&icon){window.SoftwebcoIcons.setIcon(icon,show?'bi-eye-slash':'bi-eye');}
  });
})();
</script>
</body>
</html>
