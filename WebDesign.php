<?php
class WebDesign 
{
private function version()
{
    $file = __DIR__ . '/VERSION';
    return is_file($file) ? trim((string)file_get_contents($file)) : '2.0.0';
}
/**************************************************************************************/
public function PublicBase(): string
{
    $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/.');
    if (preg_match('~/(?:backend|programs)$~i', $dir)) {
        $dir = rtrim(str_replace('\\', '/', dirname($dir)), '/.');
    }
    return ($dir === '' || $dir === '/') ? '' : $dir;
}
public function PublicUrl(string $path = ''): string
{
    $base = $this->PublicBase();
    return ($base !== '' ? $base : '') . '/' . ltrim($path, '/');
}
public function CurrentPublicSection(): string
{
    $requestPath = (string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH) ?? '');
    $requestPath = trim(str_replace('\\', '/', $requestPath), '/');
    $base = trim($this->PublicBase(), '/');
    if ($base !== '' && ($requestPath === $base || str_starts_with($requestPath, $base . '/'))) {
        $requestPath = ltrim(substr($requestPath, strlen($base)), '/');
    }

    $requestPath = preg_replace('~\\.php$~i', '', $requestPath) ?? $requestPath;
    $requestPath = trim($requestPath, '/');
    $first = strtolower((string)explode('/', $requestPath)[0]);

    if ($requestPath === '' || in_array($first, ['index', 'home'], true)) {
        return 'home';
    }
    if ($first === 'about') {
        return 'about';
    }
    if (in_array($first, ['blogs', 'blog-detail'], true)) {
        return 'blogs';
    }
    if ($first === 'contact') {
        return 'contact';
    }

    $programRoutes = [
        'programs', 'erp-program', 'car-rental-program', 'shoppingstore', 'ams',
        'pms-program', 'sms', 'ocr', 'portfolio-program', 'portfolio', 'portfolio-view'
    ];
    if (in_array($first, $programRoutes, true)) {
        return 'programs';
    }

    // When PHP is reached through an internal rewrite, REQUEST_URI may not expose
    // the physical /programs/*.php path. SCRIPT_NAME gives us a safe fallback.
    $script = strtolower(str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '')));
    if (str_contains($script, '/programs/')) {
        return 'programs';
    }
    if (str_ends_with($script, '/blog-detail.php')) {
        return 'blogs';
    }

    return '';
}
private function publicNavClass(string $key, string $baseClass): string
{
    return $baseClass . ($this->CurrentPublicSection() === $key ? ' is-active' : '');
}
private function publicNavCurrent(string $key): string
{
    return $this->CurrentPublicSection() === $key ? ' aria-current="page"' : '';
}
public function GenerateHeadTag1()
{
    $version = htmlspecialchars($this->version(), ENT_QUOTES, 'UTF-8');
    echo '    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Softwebco</title>
    <script>
    (function(){
        var n=navigator||{};
        var ua=n.userAgent||"";
        var isIPadDesktop=(n.platform==="MacIntel" && Number(n.maxTouchPoints||0)>1);
        var isMobileTablet=/Android|iPhone|iPad|iPod|Mobile|Tablet/i.test(ua)||isIPadDesktop;
        document.documentElement.classList.add(isMobileTablet?"swc-touch-ui":"swc-desktop-ui");
    })();
    </script>
    <link rel="stylesheet" href="'.$this->PublicUrl('assets/css/tailwind-local.css').'?v='.$version.'">
    <link rel="icon" href="'.$this->PublicUrl('assets/images/favicon-32.png').'" sizes="32x32" type="image/png">
    <link rel="icon" href="'.$this->PublicUrl('assets/images/favicon.png').'" type="image/png">
    <link rel="apple-touch-icon" href="'.$this->PublicUrl('assets/images/apple-touch-icon.png').'">
    <link rel="shortcut icon" href="'.$this->PublicUrl('assets/images/favicon.png').'" type="image/png" /> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Tajawal:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="'.$this->PublicUrl('assets/css/site.css').'?v='.$version.'">
    <script defer src="'.$this->PublicUrl('assets/js/icons.js').'?v='.$version.'"></script>
    <script src="'.$this->PublicUrl('assets/js/cursor.js').'?v='.$version.'"></script>';
}
public function GenerateHeadTag2()
{
    $version = htmlspecialchars($this->version(), ENT_QUOTES, 'UTF-8');
    echo '
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0a2530">
    <title>Softwebco Admin</title>
    <link rel="icon" href="../assets/images/favicon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css?v='.$version.'">
    <script defer src="../assets/js/icons.js?v='.$version.'"></script>
    <script defer src="../assets/js/admin.js?v='.$version.'"></script>';
}
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
public function ShowNavbar1()
{
    $navBase = 'nav-link px-4 py-2 rounded-md text-sm font-medium text-white hover:text-teal-300 transition duration-300';
    $mobileBase = 'nav-link block px-3 py-2 rounded-md text-base font-medium text-white hover:text-teal-300';
    $homeClass = $this->publicNavClass('home', $navBase);
    $aboutClass = $this->publicNavClass('about', $navBase);
    $programsClass = $this->publicNavClass('programs', $navBase);
    $blogsClass = $this->publicNavClass('blogs', $navBase);
    $contactClass = 'ml-6 swc-nav-cta' . ($this->CurrentPublicSection() === 'contact' ? ' is-active' : '');

    $mHomeClass = $this->publicNavClass('home', $mobileBase);
    $mAboutClass = $this->publicNavClass('about', $mobileBase);
    $mProgramsClass = $this->publicNavClass('programs', $mobileBase);
    $mBlogsClass = $this->publicNavClass('blogs', $mobileBase);
    $mContactClass = $this->publicNavClass('contact', $mobileBase);

    echo '    <!-- Navigation -->
<nav id="swcNavbar" class="fixed w-full swc-navbar text-white z-50 transition-all duration-300">
    <div class="swc-navbar-pattern"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between h-20 items-center">
            <a href="'.$this->PublicUrl('index').'" class="flex items-center space-x-3 group">
                <span class="swc-logo-mark">
                    <img src="'.$this->PublicUrl('assets/images/Logo.png').'?v='.rawurlencode($this->version()).'" alt="SoftWebCo" class="h-9 w-auto">
                </span>
                <span class="text-xl font-bold tracking-tight text-white">Soft<span class="text-teal-400">Web</span>Co</span>
            </a>

            <div class="swc-desktop-nav hidden md:flex items-center">
                <div class="flex items-baseline space-x-1">
                    <a href="'.$this->PublicUrl('index').'" class="'.$homeClass.'"'.$this->publicNavCurrent('home').'>Home</a>
                    <a href="'.$this->PublicUrl('about').'" class="'.$aboutClass.'"'.$this->publicNavCurrent('about').'>About</a>
                    <a href="'.$this->PublicUrl('programs').'" class="'.$programsClass.'"'.$this->publicNavCurrent('programs').'>Programs</a>
                    <a href="'.$this->PublicUrl('blogs').'" class="'.$blogsClass.'"'.$this->publicNavCurrent('blogs').'>Blogs</a>
                </div>
                <a href="'.$this->PublicUrl('contact').'" class="'.$contactClass.'"'.$this->publicNavCurrent('contact').'>Contact Us</a>
            </div>

            <div class="swc-mobile-toggle md:hidden">
                <button id="mobile-menu-button" class="text-white focus:outline-none" aria-label="Open navigation menu" aria-controls="mobile-menu" aria-expanded="false">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="swc-mobile-menu overflow-hidden max-h-0 transition-all duration-500 ease-in-out md:hidden swc-navbar relative">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="'.$this->PublicUrl('index').'" class="'.$mHomeClass.'"'.$this->publicNavCurrent('home').'>Home</a>
            <a href="'.$this->PublicUrl('about').'" class="'.$mAboutClass.'"'.$this->publicNavCurrent('about').'>About</a>
            <a href="'.$this->PublicUrl('programs').'" class="'.$mProgramsClass.'"'.$this->publicNavCurrent('programs').'>Programs</a>
            <a href="'.$this->PublicUrl('blogs').'" class="'.$mBlogsClass.'"'.$this->publicNavCurrent('blogs').'>Blogs</a>
            <a href="'.$this->PublicUrl('contact').'" class="'.$mContactClass.'"'.$this->publicNavCurrent('contact').'>Contact Us</a>
        </div>
    </div>
</nav>
        ';
}
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
public function ShowNavbar2($page, $current_user = null)
{
    $current_user = is_array($current_user) ? $current_user : [];
    $name = trim((string)($current_user['display_name'] ?? '')) ?: trim((string)($current_user['username'] ?? 'Softwebco Admin')) ?: 'Softwebco Admin';
    $profile = $current_user['profile_image'] ?? 'default-avatar.png';
    $version = htmlspecialchars($this->version(), ENT_QUOTES, 'UTF-8');
    $titles = [
        'admin' => ['Dashboard', 'Your website content at a glance.'],
        'users' => ['Users', 'Manage administrator access.'],
        'bloglist' => ['Blogs', 'Create and manage website articles.'],
        'categories' => ['Categories', 'Organize the filters used by your public blog.'],
    ];
    [$title, $subtitle] = $titles[$page] ?? ['Admin', 'Softwebco workspace'];
    $active = in_array($page, ['admin','users','bloglist','categories'], true) ? $page : 'admin';
    $avatar = swc_admin_profile_url((string)$profile);
    $initials = swc_admin_initials($name);

    echo '<div class="swc-admin-shell">';
    echo '<div class="swc-sidebar-backdrop" id="swcSidebarBackdrop" aria-hidden="true"></div>';
    echo '<aside class="swc-sidebar" id="swcSidebar" aria-label="Admin navigation">';
    echo '<div class="swc-sidebar-brand"><a href="admin.php" class="swc-brand-link"><span class="swc-brand-logo"><img src="../assets/images/Logo.png?v='.$version.'" alt="Softwebco"></span><span class="swc-brand-copy"><strong>Softwebco</strong><small>Content Admin</small></span></a><button class="swc-sidebar-close" type="button" data-sidebar-close aria-label="Close menu"><i class="bi bi-x-lg"></i></button></div>';
    echo '<div class="swc-sidebar-label">Workspace</div>';
    echo '<nav class="swc-sidebar-nav">';
    $items = [
        'admin' => ['admin.php','bi-grid-1x2-fill','Dashboard'],
        'users' => ['users.php','bi-people-fill','Users'],
        'bloglist' => ['bloglist.php','bi-journal-richtext','Blogs'],
        'categories' => ['categories.php','bi-tags-fill','Categories'],
    ];
    foreach ($items as $key => $item) {
        $is = $active === $key ? ' is-active' : '';
        echo '<a class="swc-sidebar-link'.$is.'" href="'.$item[0].'"><span class="swc-sidebar-icon"><i class="bi '.$item[1].'"></i></span><span>'.$item[2].'</span></a>';
    }
    echo '</nav>';
    echo '<div class="swc-sidebar-foot"><a href="../index.php" target="_blank" rel="noopener"><i class="bi bi-globe2"></i><span>Open website</span></a><a href="logout.php" class="swc-sidebar-logout"><i class="bi bi-box-arrow-right"></i><span>Log out</span></a><small>SOFTWEBCO · v'.$version.'</small></div>';
    echo '</aside>';

    echo '<div class="swc-admin-stage">';
    echo '<header class="swc-topbar"><div class="swc-topbar-left"><button class="swc-navbar-toggle" id="swcNavbarToggle" type="button" aria-controls="swcSidebar" aria-expanded="false" aria-label="Open menu"><span class="swc-hamburger" aria-hidden="true"><span></span><span></span><span></span></span></button><div class="swc-page-copy"><span class="swc-page-kicker">Softwebco Admin</span><h1>'.$title.'</h1><p>'.$subtitle.'</p></div></div>';
    echo '<div class="swc-topbar-right"><a class="swc-site-link" href="../index.php" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i><span>View website</span></a><div class="swc-admin-person"><span class="swc-admin-avatar"><img src="'.htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8').'" alt="" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'grid\';"><b style="display:none">'.htmlspecialchars($initials, ENT_QUOTES, 'UTF-8').'</b></span><span class="swc-admin-person-copy"><strong>'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'</strong><small>Administrator</small></span><a href="logout.php" class="swc-icon-button" aria-label="Log out" title="Log out"><i class="bi bi-box-arrow-right"></i></a></div></div></header>';
    echo '<main class="swc-admin-content">';
}
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
/**************************************************************************************/
public function CloseAdminLayout()
{
    echo '</main></div></div>';
}
/**************************************************************************************/
public function Showfooter()
{
     echo '
    <footer class="swc-footer text-white py-14 px-4 sm:px-6 lg:px-8">
        <div class="swc-footer-pattern"></div>
        <div class="max-w-7xl mx-auto relative">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="' . $this->PublicUrl('assets/images/Logo.png') . '?v=' . rawurlencode($this->version()) . '" alt="SoftWebCo" class="h-8 w-auto">
                        <h3 class="text-xl font-bold text-white">Soft<span class="text-teal-400">Web</span>Co</h3>
                    </div>
                    <p class="text-gray-300">Crafting digital experiences that inspire and deliver results for businesses worldwide.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="'.$this->PublicUrl('index').'" class="text-gray-300 hover:text-teal-400 transition duration-300">Home</a></li>
                        <li><a href="'.$this->PublicUrl('about').'" class="text-gray-300 hover:text-teal-400 transition duration-300">About Us</a></li>
                        <li><a href="'.$this->PublicUrl('programs').'" class="text-gray-300 hover:text-teal-400 transition duration-300">Our Programs</a></li>
                        <li><a href="'.$this->PublicUrl('portfolio').'" class="text-gray-300 hover:text-teal-400 transition duration-300">Portfolio</a></li>
                        <li><a href="'.$this->PublicUrl('blogs').'" class="text-gray-300 hover:text-teal-400 transition duration-300">Blogs</a></li>
                        <li><a href="'.$this->PublicUrl('contact').'" class="text-gray-300 hover:text-teal-400 transition duration-300">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="'.$this->PublicUrl('programs').'" class="text-gray-300 hover:text-teal-400 transition duration-300">Web Design</a></li>
                        <li><a href="'.$this->PublicUrl('programs').'" class="text-gray-300 hover:text-teal-400 transition duration-300">Web Development</a></li>
                        <li><a href="'.$this->PublicUrl('programs').'" class="text-gray-300 hover:text-teal-400 transition duration-300">E-Commerce</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Social Media</h4>
                   <div class="flex space-x-4">
                <a href="https://www.facebook.com/profile.php?id=61578804990939" target="_blank" class="swc-social-icon">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="https://www.tiktok.com/@softwebco" target="_blank" class="swc-social-icon">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://www.instagram.com/softwebco/" target="_blank" class="swc-social-icon">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2026 SoftWebCo. All rights reserved. <span class="text-gray-500">v' . $this->version() . '</span></p>
                <div class="flex space-x-6">
                </div>
            </div>
        </div>
    </footer>

';
}
}
?>
