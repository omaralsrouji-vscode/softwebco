<?php
include("WebDesign.php");

$design = new WebDesign(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php
    $design->GenerateHeadTag1();
?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }    
        .feature-icon {
            transition: all 0.3s ease;
        }

.card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .category-badge {
            transition: all 0.2s ease;
        }
        
        .card:hover .category-badge {
            background-color: #0B202D;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .floating {
            animation: float 3s ease-in-out infinite;
        }

        .swc-feature-card {
            background: #ffffff;
            border-radius: 1rem;
            padding: 2rem 1.75rem;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }

        .swc-feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(11, 32, 45, 0.1), 0 10px 10px -5px rgba(11, 32, 45, 0.04);
            border-color: rgba(32, 188, 169, 0.35);
        }

        .swc-feature-icon {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 0.85rem;
            background: linear-gradient(135deg, #20bca9, #0B202D);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1.1rem;
            transition: transform 0.3s ease;
        }

        .swc-feature-card:hover .swc-feature-icon {
            transform: rotate(-8deg) scale(1.08);
        }

        .swc-popular-ribbon {
            position: absolute;
            top: 1rem;
            right: -2.6rem;
            transform: rotate(45deg);
            background: #0B202D;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.35rem 3rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            z-index: 10;
        }

        .swc-testimonial-card {
            background: #F7FAFA;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .swc-testimonial-card:hover {
            box-shadow: 0 20px 25px -5px rgba(11, 32, 45, 0.08);
            transform: translateY(-6px);
        }

        .swc-avatar {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 9999px;
            background: linear-gradient(135deg, #20bca9, #0B202D);
            color: #fff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        /* Featured Systems v2.0.4 */
        .swc-systems-section {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 8% 12%, rgba(32,188,169,.18), transparent 28rem),
                radial-gradient(circle at 92% 82%, rgba(32,188,169,.09), transparent 30rem),
                #071a24;
        }
        .swc-systems-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('assets/images/brand-pattern-teal.png');
            background-size: 760px;
            background-position: right -140px top -90px;
            background-repeat: no-repeat;
            opacity: .045;
            pointer-events: none;
        }
        .swc-systems-head {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 2.25rem;
        }
        .swc-systems-kicker {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            margin-bottom: .9rem;
            color: #62ddcf;
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        .swc-systems-kicker::before {
            content: "";
            width: 1.65rem;
            height: 2px;
            background: #20bca9;
            border-radius: 999px;
        }
        .swc-systems-title {
            color: #fff;
            font-size: clamp(2rem, 4vw, 3.55rem);
            line-height: 1.02;
            letter-spacing: -.035em;
            font-weight: 750;
        }
        .swc-systems-subtitle {
            max-width: 43rem;
            margin-top: 1rem;
            color: #9eb2bb;
            font-size: 1rem;
            line-height: 1.75;
        }
        .swc-systems-all {
            flex: none;
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            padding: .86rem 1.15rem;
            border: 1px solid rgba(113,225,211,.22);
            border-radius: 999px;
            background: rgba(255,255,255,.04);
            color: #dff8f5;
            font-size: .83rem;
            font-weight: 600;
            transition: .3s ease;
        }
        .swc-systems-all:hover {
            color: #071a24;
            background: #65e0d1;
            border-color: #65e0d1;
            transform: translateY(-2px);
        }
        .swc-systems-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 1.25rem;
        }
        .swc-system-card {
            position: relative;
            min-height: 390px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 1.6rem;
            background: #0b2632;
            box-shadow: 0 22px 60px rgba(0,0,0,.18);
            isolation: isolate;
            transition: transform .35s ease, border-color .35s ease, box-shadow .35s ease;
        }
        .swc-system-card:nth-child(1), .swc-system-card:nth-child(4) { grid-column: span 7; }
        .swc-system-card:nth-child(2), .swc-system-card:nth-child(3) { grid-column: span 5; }
        .swc-system-card:hover {
            transform: translateY(-5px);
            border-color: rgba(101,224,209,.45);
            box-shadow: 0 28px 80px rgba(0,0,0,.28);
        }
        .swc-system-card img {
            position: absolute;
            inset: 0;
            z-index: -3;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
            transform: scale(1.015);
            transition: transform .65s cubic-bezier(.2,.75,.2,1), filter .45s ease;
        }
        .swc-system-card:hover img { transform: scale(1.06); }
        .swc-system-card::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -2;
            background: linear-gradient(180deg, rgba(3,17,23,.08) 4%, rgba(3,17,23,.2) 38%, rgba(3,17,23,.94) 100%);
        }
        .swc-system-card::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(120deg, rgba(32,188,169,.10), transparent 45%);
            opacity: 0;
            transition: opacity .35s ease;
        }
        .swc-system-card:hover::after { opacity: 1; }
        .swc-system-hit { position: absolute; inset: 0; z-index: 1; }
        .swc-system-top {
            position: absolute;
            top: 1.15rem;
            left: 1.15rem;
            right: 1.15rem;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            pointer-events: none;
        }
        .swc-system-type, .swc-system-live {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .48rem .75rem;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 999px;
            background: rgba(5,25,33,.68);
            backdrop-filter: blur(10px);
            color: #ebfffc;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .swc-system-live { color: #8cf0e4; }
        .swc-system-live::before {
            content: "";
            width: .4rem;
            height: .4rem;
            border-radius: 50%;
            background: #20bca9;
            box-shadow: 0 0 0 4px rgba(32,188,169,.14);
        }
        .swc-system-number {
            color: rgba(255,255,255,.62);
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .08em;
        }
        .swc-system-copy {
            position: absolute;
            left: 1.15rem;
            right: 1.15rem;
            bottom: 1.1rem;
            z-index: 3;
            padding: 1.05rem 1.1rem;
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 1.05rem;
            background: linear-gradient(135deg, rgba(4,23,31,.88), rgba(4,23,31,.68));
            backdrop-filter: blur(10px);
            box-shadow: 0 16px 42px rgba(0,0,0,.18);
            pointer-events: none;
        }
        .swc-system-copy h3 {
            max-width: 28rem;
            color: #fff;
            font-size: clamp(1.35rem, 2.2vw, 2rem);
            line-height: 1.13;
            letter-spacing: -.025em;
            font-weight: 700;
        }
        .swc-system-copy p {
            max-width: 37rem;
            margin-top: .62rem;
            color: #b9cbd1;
            font-size: .86rem;
            line-height: 1.65;
        }
        .swc-system-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: .9rem;
            border-top: 1px solid rgba(255,255,255,.11);
        }
        .swc-system-tags { display: flex; flex-wrap: wrap; gap: .42rem; }
        .swc-system-tags span {
            color: #8fa6ae;
            font-size: .69rem;
            font-weight: 500;
        }
        .swc-system-tags span + span::before { content: "·"; margin-right: .42rem; color: #4d6871; }
        .swc-system-actions { position: relative; z-index: 4; display: flex; align-items: center; gap: .55rem; pointer-events: auto; }
        .swc-system-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.55rem;
            height: 2.55rem;
            padding: 0 .85rem;
            border-radius: 999px;
            background: #20bca9;
            color: #05231f;
            font-size: .75rem;
            font-weight: 700;
            transition: transform .25s ease, background .25s ease;
        }
        .swc-system-action:hover { transform: scale(1.04); background: #73e4d6; }
        .swc-system-action.icon-only { width: 2.55rem; min-width: 2.55rem; padding: 0; }
        .swc-system-action.secondary { background: rgba(255,255,255,.1); color: #fff; border: 1px solid rgba(255,255,255,.13); }
        .swc-system-action.secondary:hover { background: rgba(255,255,255,.18); }
        @media (max-width: 1023px) {
            .swc-systems-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .swc-system-card:nth-child(n) { grid-column: span 1; min-height: 365px; }
        }
        @media (max-width: 767px) {
            .swc-systems-section { padding-top: 4.5rem !important; padding-bottom: 4.5rem !important; }
            .swc-systems-head { align-items: flex-start; flex-direction: column; margin-bottom: 1.65rem; }
            .swc-systems-all { padding: .72rem 1rem; }
            .swc-systems-grid { grid-template-columns: 1fr; gap: .9rem; }
            .swc-system-card:nth-child(n) { min-height: 355px; }
            .swc-system-copy { left: .85rem; right: .85rem; bottom: .85rem; padding: .9rem; }
            .swc-system-copy p { font-size: .8rem; }
            .swc-system-bottom { align-items: flex-end; }
            .swc-system-tags { max-width: 65%; }
            .swc-system-action .label { display: none; }
            .swc-system-action { width: 2.55rem; min-width: 2.55rem; padding: 0; }
        }

    </style>
</head>
<body>
    <!-- Cursor Follower -->
    <div class="cursor-follower"></div>
    
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loading-text">Hello dear...</div>
    </div>

    
    <!-- Navigation -->  
     
     
     
<?php
    $design->ShowNavbar1();
?>


    
    <!-- Hero Section -->
    <section id="home" class="relative overflow-hidden pt-32 pb-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#0B202D] to-[#2D3945] text-white">
        <div class="absolute inset-0 pointer-events-none" style="background-image:url('assets/images/brand-pattern-teal.png');background-size:900px;background-position:right -200px top -150px;background-repeat:no-repeat;opacity:0.08;"></div>
        <div class="max-w-7xl mx-auto relative">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                <div class="fade-in">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-teal-400/30 text-teal-300 text-xs font-semibold tracking-wide uppercase mb-6">
                        <i class="fas fa-code"></i> Web Design &amp; Development Studio
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">Crafting Digital Experiences That <span class="text-teal-400">Inspire</span></h1>
                    <p class="text-lg md:text-xl mb-8 text-gray-300">We build custom websites that elevate your brand and drive results. Beautiful design meets powerful functionality.</p>
                    <div class="flex flex-wrap gap-4 mb-10">
                        <a href="programs" class="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition duration-300 transform hover:scale-105">Our Programs</a>
                        <a href="contact" class="px-8 py-3 bg-white/5 border border-white/20 hover:bg-white/10 text-white font-medium rounded-lg transition duration-300">Get in Touch</a>
                    </div>
                    <div class="flex items-center gap-6 text-sm text-gray-400">
                        <div class="flex items-center gap-2"><i class="fas fa-star text-yellow-400"></i> Trusted delivery process</div>
                        <div class="flex items-center gap-2"><i class="fas fa-lock text-teal-400"></i> Secure, scalable code</div>
                    </div>
                </div>
                <div class="mt-14 lg:mt-0 fade-in">
                    <div class="relative">
                        <div class="absolute -top-6 -left-6 w-64 h-64 bg-teal-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                        <div class="absolute -bottom-8 -right-8 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                        <div class="relative swc-browser-mock floating">
                            <div class="swc-browser-bar">
                                <span class="swc-browser-dot" style="background:#ff5f57;"></span>
                                <span class="swc-browser-dot" style="background:#febc2e;"></span>
                                <span class="swc-browser-dot" style="background:#28c840;"></span>
                                <span class="swc-browser-url"><i class="fas fa-lock mr-1" style="font-size:0.6rem;"></i>softwebco.com</span>
                            </div>
                            <div class="swc-browser-body">
                                <div class="flex items-center gap-3 mb-5">
                                    <img src="assets/images/Logo.png" alt="SoftWebCo" class="h-7 w-auto">
                                    <div class="swc-skel-line" style="width:35%;margin-bottom:0;"></div>
                                </div>
                                <div class="swc-skel-line" style="width:80%;"></div>
                                <div class="swc-skel-line" style="width:60%;"></div>
                                <div class="grid grid-cols-3 gap-3 mt-4">
                                    <div class="swc-skel-block"></div>
                                    <div class="swc-skel-block"></div>
                                    <div class="swc-skel-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech / Trust Marquee -->
    <section class="bg-white border-b border-gray-100 py-4">
        <div class="swc-marquee">
            <div class="swc-marquee-track">
                <div class="swc-marquee-item"><i class="fab fa-php"></i> PHP</div>
                <div class="swc-marquee-item"><i class="fas fa-database"></i> MySQL</div>
                <div class="swc-marquee-item"><i class="fab fa-js"></i> JavaScript ES6+</div>
                <div class="swc-marquee-item"><i class="fab fa-bootstrap"></i> Bootstrap 5</div>
                <div class="swc-marquee-item"><i class="fas fa-shield-halved"></i> Secure by Design</div>
                <div class="swc-marquee-item"><i class="fas fa-mobile-screen"></i> Fully Responsive</div>
                <div class="swc-marquee-item"><i class="fas fa-gauge-high"></i> Optimized for Speed</div>
                <div class="swc-marquee-item"><i class="fas fa-headset"></i> Ongoing Support</div>
                <!-- duplicate for seamless loop -->
                <div class="swc-marquee-item"><i class="fab fa-php"></i> PHP</div>
                <div class="swc-marquee-item"><i class="fas fa-database"></i> MySQL</div>
                <div class="swc-marquee-item"><i class="fab fa-js"></i> JavaScript ES6+</div>
                <div class="swc-marquee-item"><i class="fab fa-bootstrap"></i> Bootstrap 5</div>
                <div class="swc-marquee-item"><i class="fas fa-shield-halved"></i> Secure by Design</div>
                <div class="swc-marquee-item"><i class="fas fa-mobile-screen"></i> Fully Responsive</div>
                <div class="swc-marquee-item"><i class="fas fa-gauge-high"></i> Optimized for Speed</div>
                <div class="swc-marquee-item"><i class="fas fa-headset"></i> Ongoing Support</div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-4">About SoftWebCo</h2>
                <div class="w-20 h-1 bg-teal-500 mx-auto"></div>
            </div>
            
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                <div class="mb-12 lg:mb-0 fade-in">
                    <div class="relative">
                        <div class="absolute -top-5 -left-5 w-24 h-24 border-4 border-teal-500 rounded-2xl -z-10"></div>
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Our Team" class="rounded-2xl shadow-2xl w-full">
                        <div class="absolute -bottom-6 -right-6 bg-white rounded-xl shadow-xl px-6 py-4 flex items-center gap-4 border border-gray-100">
                            <img src="assets/images/logo-navy.png" alt="SoftWebCo" class="h-10 w-auto">
                            <div>
                                <div class="text-2xl font-bold text-[#0B202D] leading-none">5+ yrs</div>
                                <div class="text-xs text-gray-500 mt-1">Building digital products</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fade-in">
                    <h3 class="text-2xl md:text-3xl font-bold text-[#2D3945] mb-6">We're more than just web developers</h3>
                    <p class="text-gray-600 mb-6">We fuse smart strategy with bold creativity to build more than just websites we create meaningful digital journeys. Every project is a chance to tell your story in a way that engages, inspires, and leaves a lasting impression.</p>
                    <p class="text-gray-600 mb-6">Our approach combines technical excellence with creative design thinking. We don't just build websites - we craft digital experiences that tell your brand's story and connect with your audience.</p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">Custom solutions tailored to your specific needs</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">Modern, responsive designs that work on any device</p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">Ongoing support and maintenance after launch</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-[#F7FAFA]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-4">Why Businesses Choose Us</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Everything you need from a development partner, in one place</p>
                <div class="w-20 h-1 bg-teal-500 mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="swc-feature-card fade-in">
                    <div class="swc-feature-icon"><i class="fas fa-bolt"></i></div>
                    <h3 class="text-lg font-bold text-[#0B202D] mb-2">Fast Delivery</h3>
                    <p class="text-gray-600 text-sm">Streamlined process that gets your project live without compromising on quality.</p>
                </div>
                <div class="swc-feature-card fade-in">
                    <div class="swc-feature-icon"><i class="fas fa-code"></i></div>
                    <h3 class="text-lg font-bold text-[#0B202D] mb-2">Clean Code</h3>
                    <p class="text-gray-600 text-sm">Scalable, well-structured code built on modern best practices from day one.</p>
                </div>
                <div class="swc-feature-card fade-in">
                    <div class="swc-feature-icon"><i class="fas fa-headset"></i></div>
                    <h3 class="text-lg font-bold text-[#0B202D] mb-2">Real Support</h3>
                    <p class="text-gray-600 text-sm">A dedicated team on hand after launch, not a ticket queue that goes quiet.</p>
                </div>
                <div class="swc-feature-card fade-in">
                    <div class="swc-feature-icon"><i class="fas fa-shield-halved"></i></div>
                    <h3 class="text-lg font-bold text-[#0B202D] mb-2">Secure by Design</h3>
                    <p class="text-gray-600 text-sm">Every build follows security best practices to keep your data and users safe.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How We Work Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                <div class="mb-12 lg:mb-0 fade-in">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-50 text-teal-700 text-xs font-semibold tracking-wide uppercase mb-5">
                        <i class="fas fa-terminal"></i> Our Process
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-5">How We Work, Step by Step</h2>
                    <p class="text-gray-600 mb-6 text-lg">No black boxes. Every project moves through the same clear pipeline &mdash; so you always know exactly where things stand.</p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</div>
                            <p class="text-gray-600"><span class="font-semibold text-[#2D3945]">Discovery</span> &mdash; we learn your goals, audience, and must-haves.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</div>
                            <p class="text-gray-600"><span class="font-semibold text-[#2D3945]">Design &amp; Build</span> &mdash; clean interfaces backed by solid, tested code.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</div>
                            <p class="text-gray-600"><span class="font-semibold text-[#2D3945]">Launch &amp; Support</span> &mdash; we ship it, then stay on to keep it running well.</p>
                        </div>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="swc-buildlog">
                        <div class="swc-buildlog-header">
                            <span class="swc-browser-dot" style="background:#ff5f57;"></span>
                            <span class="swc-browser-dot" style="background:#febc2e;"></span>
                            <span class="swc-browser-dot" style="background:#28c840;"></span>
                            <span class="ml-2 text-xs text-gray-400">build.log</span>
                        </div>
                        <div class="swc-log-line"><span class="swc-log-step">[01]</span> Analyzing project requirements... <i class="fas fa-check text-teal-400 ml-auto"></i></div>
                        <div class="swc-log-line"><span class="swc-log-step">[02]</span> Designing UI / UX layout... <i class="fas fa-check text-teal-400 ml-auto"></i></div>
                        <div class="swc-log-line"><span class="swc-log-step">[03]</span> Writing clean, scalable code... <i class="fas fa-check text-teal-400 ml-auto"></i></div>
                        <div class="swc-log-line"><span class="swc-log-step">[04]</span> Running tests &amp; QA checks... <i class="fas fa-check text-teal-400 ml-auto"></i></div>
                        <div class="swc-log-line"><span class="swc-log-step">[05]</span> Deploying to production<span class="swc-log-cursor"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Build For Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-[#F7FAFA]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-4">Who We Build For</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Custom digital solutions for every kind of business</p>
                <div class="w-20 h-1 bg-teal-500 mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
                <div class="swc-audience-card fade-in">
                    <div class="swc-audience-icon"><i class="fas fa-rocket"></i></div>
                    <p class="text-sm font-semibold text-[#2D3945]">Startups</p>
                </div>
                <div class="swc-audience-card fade-in">
                    <div class="swc-audience-icon"><i class="fas fa-cart-shopping"></i></div>
                    <p class="text-sm font-semibold text-[#2D3945]">Retail &amp; E-commerce</p>
                </div>
                <div class="swc-audience-card fade-in">
                    <div class="swc-audience-icon"><i class="fas fa-house-medical"></i></div>
                    <p class="text-sm font-semibold text-[#2D3945]">Healthcare</p>
                </div>
                <div class="swc-audience-card fade-in">
                    <div class="swc-audience-icon"><i class="fas fa-building"></i></div>
                    <p class="text-sm font-semibold text-[#2D3945]">Real Estate</p>
                </div>
                <div class="swc-audience-card fade-in">
                    <div class="swc-audience-icon"><i class="fas fa-utensils"></i></div>
                    <p class="text-sm font-semibold text-[#2D3945]">Restaurants</p>
                </div>
                <div class="swc-audience-card fade-in">
                    <div class="swc-audience-icon"><i class="fas fa-building-columns"></i></div>
                    <p class="text-sm font-semibold text-[#2D3945]">Enterprises</p>
                </div>
            </div>
        </div>
    </section>

    
    
    <!-- Featured Systems -->
    <section id="websites" class="swc-systems-section py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="swc-systems-head fade-in">
                <div>
                    <span class="swc-systems-kicker">Built by Softwebco</span>
                    <h2 class="swc-systems-title">Featured Systems</h2>
                    <p class="swc-systems-subtitle">Purpose-built digital products designed around real workflows — from daily operations and sales to customer-facing experiences.</p>
                </div>
                <a href="programs" class="swc-systems-all">Explore all programs <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="swc-systems-grid">
                <article class="swc-system-card fade-in">
                    <img src="assets/images/programs/erp-system.png" alt="ERP & POS Management System dashboard" loading="lazy">
                    <a class="swc-system-hit" href="erp-program" aria-label="View ERP & POS Management System"></a>
                    <div class="swc-system-top"><span class="swc-system-type">ERP + POS</span><span class="swc-system-number">01</span></div>
                    <div class="swc-system-copy">
                        <h3>ERP &amp; POS Management System</h3>
                        <p>Sales, inventory, purchasing, suppliers, accounting, expenses, reporting and branches connected in one workspace.</p>
                        <div class="swc-system-bottom">
                            <div class="swc-system-tags"><span>Sales</span><span>Stock</span><span>Accounting</span></div>
                            <div class="swc-system-actions"><a class="swc-system-action" href="erp-program"><span class="label">Explore</span><i class="fas fa-arrow-right ml-2"></i></a></div>
                        </div>
                    </div>
                </article>

                <article class="swc-system-card fade-in">
                    <img src="assets/images/programs/car-rental-system.png" alt="Car Rental Management System dashboard" loading="lazy">
                    <a class="swc-system-hit" href="car-rental-program" aria-label="View Car Rental Management System"></a>
                    <div class="swc-system-top"><span class="swc-system-type">Car Rental</span><span class="swc-system-number">02</span></div>
                    <div class="swc-system-copy">
                        <h3>Car Rental Management System</h3>
                        <p>Fleet, reservations, rental contracts, invoices, maintenance, insurance, expenses and alerts.</p>
                        <div class="swc-system-bottom">
                            <div class="swc-system-tags"><span>Fleet</span><span>Rentals</span><span>Billing</span></div>
                            <div class="swc-system-actions"><a class="swc-system-action icon-only" href="car-rental-program" aria-label="Explore Car Rental System"><i class="fas fa-arrow-right"></i></a></div>
                        </div>
                    </div>
                </article>

                <article class="swc-system-card fade-in">
                    <img src="assets/images/cards/Shopping-application.png" alt="E-commerce System" loading="lazy">
                    <a class="swc-system-hit" href="shoppingstore" aria-label="View E-commerce System"></a>
                    <div class="swc-system-top"><span class="swc-system-type">E-commerce</span><span class="swc-system-number">03</span></div>
                    <div class="swc-system-copy">
                        <h3>E-commerce System</h3>
                        <p>A responsive storefront with product catalog, cart, checkout, orders and administration.</p>
                        <div class="swc-system-bottom">
                            <div class="swc-system-tags"><span>Catalog</span><span>Checkout</span><span>Orders</span></div>
                            <div class="swc-system-actions"><a class="swc-system-action icon-only" href="shoppingstore" aria-label="Explore E-commerce System"><i class="fas fa-arrow-right"></i></a></div>
                        </div>
                    </div>
                </article>

                <article class="swc-system-card fade-in">
                    <img src="assets/images/brand-pattern-teal.png" alt="Portfolio website" loading="lazy">
                    <a class="swc-system-hit" href="portfolio-program" aria-label="View Portfolio program"></a>
                    <div class="swc-system-top"><span class="swc-system-type">Portfolio</span><span class="swc-system-live">Responsive design</span></div>
                    <div class="swc-system-copy">
                        <h3>Interactive Portfolio</h3>
                        <p>A premium responsive portfolio with motion, strong visual identity and an interactive Three.js journey.</p>
                        <div class="swc-system-bottom">
                            <div class="swc-system-tags"><span>Frontend</span><span>Motion</span><span>Three.js</span></div>
                            <div class="swc-system-actions">
                                <a class="swc-system-action icon-only" href="portfolio-program" aria-label="Explore Portfolio program"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-4">What Our Clients Say</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Real feedback from businesses we've helped grow online</p>
                <div class="w-20 h-1 bg-teal-500 mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="swc-testimonial-card fade-in">
                    <div class="swc-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <i class="fas fa-quote-left text-teal-500 text-2xl mb-4 block"></i>
                    <p class="text-gray-600 mb-6">"SoftWebCo delivered our platform ahead of schedule and it looked better than we imagined. Communication was smooth the entire way."</p>
                    <div class="flex items-center gap-3">
                        <div class="swc-avatar">R</div>
                        <div>
                            <div class="font-semibold text-[#0B202D]">Rami Nasser</div>
                            <div class="text-sm text-gray-500">Retail Business Owner</div>
                        </div>
                    </div>
                </div>
                <div class="swc-testimonial-card fade-in">
                    <div class="swc-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <i class="fas fa-quote-left text-teal-500 text-2xl mb-4 block"></i>
                    <p class="text-gray-600 mb-6">"The custom system they built streamlined our entire booking process. Support after launch has been fast and genuinely helpful."</p>
                    <div class="flex items-center gap-3">
                        <div class="swc-avatar">L</div>
                        <div>
                            <div class="font-semibold text-[#0B202D]">Layla Haddad</div>
                            <div class="text-sm text-gray-500">Operations Manager</div>
                        </div>
                    </div>
                </div>
                <div class="swc-testimonial-card fade-in">
                    <div class="swc-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <i class="fas fa-quote-left text-teal-500 text-2xl mb-4 block"></i>
                    <p class="text-gray-600 mb-6">"Professional from the first call to final delivery. Our new site is fast, clean, and exactly matched our brand identity."</p>
                    <div class="flex items-center gap-3">
                        <div class="swc-avatar">K</div>
                        <div>
                            <div class="font-semibold text-[#0B202D]">Karim Fares</div>
                            <div class="text-sm text-gray-500">Startup Founder</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="relative overflow-hidden py-16 bg-[#0B202D] text-white">
        <div class="absolute inset-0 pointer-events-none" style="background-image:url('assets/images/brand-pattern-navy.png');background-size:500px;background-repeat:repeat;opacity:0.15;"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="fade-in">
                    <i class="fas fa-briefcase text-teal-400 text-2xl mb-3 block"></i>
                    <div class="text-4xl md:text-5xl font-bold mb-2 animate-bounce-slow">150+</div>
                    <div class="text-gray-300">Projects Completed</div>
                </div>
                <div class="fade-in">
                    <i class="fas fa-face-smile text-teal-400 text-2xl mb-3 block"></i>
                    <div class="text-4xl md:text-5xl font-bold mb-2 animate-bounce-slow animation-delay-200">95%</div>
                    <div class="text-gray-300">Client Satisfaction</div>
                </div>
                <div class="fade-in">
                    <i class="fas fa-users text-teal-400 text-2xl mb-3 block"></i>
                    <div class="text-4xl md:text-5xl font-bold mb-2 animate-bounce-slow animation-delay-400">50+</div>
                    <div class="text-gray-300">Happy Clients</div>
                </div>
                <div class="fade-in">
                    <i class="fas fa-headset text-teal-400 text-2xl mb-3 block"></i>
                    <div class="text-4xl md:text-5xl font-bold mb-2 animate-bounce-slow animation-delay-600">24/7</div>
                    <div class="text-gray-300">Support Available</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="relative overflow-hidden py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-teal-600 to-teal-500 text-white text-center">
        <div class="absolute inset-0 pointer-events-none" style="background-image:url('assets/images/brand-pattern-teal.png');background-size:500px;background-repeat:repeat;opacity:0.12;"></div>
        <div class="max-w-3xl mx-auto relative fade-in">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Build Something Great?</h2>
            <p class="text-lg text-teal-50 mb-8">Let's turn your idea into a fast, beautiful, and reliable website or system.</p>
            <a href="contact" class="inline-block px-8 py-3 bg-white text-teal-700 font-semibold rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-xl">Start Your Project</a>
        </div>
    </section>
    

    
    <!-- Footer -->
<?php
    $design->showfooter();
?>

    
    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 bg-teal-600 text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-teal-700">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/site.js?<?php echo date("his"); ?>"></script>


</body>
</html>
