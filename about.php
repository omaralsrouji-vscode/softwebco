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
.animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        .animate-float-delay {
            animation: float 6s ease-in-out infinite;
            animation-delay: 2s;
        }
        
        .timeline-item:not(:last-child):after {
            content: '';
            position: absolute;
            left: 7px;
            top: 24px;
            height: calc(100% - 24px);
            width: 2px;
            background: #0d948b;
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Cursor Follower -->
    <div class="cursor-follower"></div>
    
    <!-- Loading Screen -->
    <div class="loading-screen">
        <div class="loading-text">Loading..</div>
    </div>

    
    <!-- Navigation -->

 <?php
    $design->ShowNavbar1();
?>









   <!-- Hero Section -->
    <section class="relative overflow-hidden pt-32 pb-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#0B202D] to-[#2D3945] text-white">
        <div class="absolute inset-0 pointer-events-none" style="background-image:url('assets/images/brand-pattern-teal.png');background-size:900px;background-position:center top -150px;background-repeat:no-repeat;opacity:0.08;"></div>
        <div class="max-w-7xl mx-auto relative">
            <div class="text-center">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-teal-400/30 text-teal-300 text-xs font-semibold tracking-wide uppercase mb-6 fade-in">
                    <i class="fas fa-heart"></i> Who We Are
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight fade-in">Our <span class="text-teal-400">Story</span> &amp; Values</h1>
                <p class="text-lg md:text-xl mb-8 text-gray-300 max-w-3xl mx-auto fade-in">Discover the passion, expertise, and vision that drive SoftWebCo to create exceptional digital experiences.</p>
                <div class="w-20 h-1 bg-teal-500 mx-auto fade-in"></div>
            </div>
        </div>
    </section>

    <!-- Trust Marquee -->
    <section class="bg-white border-b border-gray-100 py-4">
        <div class="swc-marquee">
            <div class="swc-marquee-track">
                <div class="swc-marquee-item"><i class="fas fa-lightbulb"></i> Innovation</div>
                <div class="swc-marquee-item"><i class="fas fa-handshake"></i> Integrity</div>
                <div class="swc-marquee-item"><i class="fas fa-heart"></i> Passion</div>
                <div class="swc-marquee-item"><i class="fas fa-code"></i> Clean Code</div>
                <div class="swc-marquee-item"><i class="fas fa-headset"></i> Real Support</div>
                <div class="swc-marquee-item"><i class="fas fa-shield-halved"></i> Secure by Design</div>
                <div class="swc-marquee-item"><i class="fas fa-lightbulb"></i> Innovation</div>
                <div class="swc-marquee-item"><i class="fas fa-handshake"></i> Integrity</div>
                <div class="swc-marquee-item"><i class="fas fa-heart"></i> Passion</div>
                <div class="swc-marquee-item"><i class="fas fa-code"></i> Clean Code</div>
                <div class="swc-marquee-item"><i class="fas fa-headset"></i> Real Support</div>
                <div class="swc-marquee-item"><i class="fas fa-shield-halved"></i> Secure by Design</div>
            </div>
        </div>
    </section>
    
    <!-- About Intro -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                <div class="mb-12 lg:mb-0 fade-in">
                    <div class="relative">
                        <div class="absolute -top-5 -left-5 w-24 h-24 border-4 border-teal-500 rounded-2xl -z-10"></div>
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Our Team" class="rounded-2xl shadow-2xl w-full">
                        <div class="absolute -bottom-6 -right-6 bg-white rounded-xl shadow-xl px-6 py-4 flex items-center gap-4 border border-gray-100">
                            <img src="assets/images/logo-navy.png" alt="SoftWebCo" class="h-10 w-auto">
                            <div>
                                <div class="text-2xl font-bold text-[#0B202D] leading-none">2020</div>
                                <div class="text-xs text-gray-500 mt-1">Where it all began</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fade-in">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-50 text-teal-700 text-xs font-semibold tracking-wide uppercase mb-5">
                        <i class="fas fa-users"></i> Our Story
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-6">Who We Are</h2>
                    <p class="text-gray-600 mb-6">Founded in 2020, SoftWebCo began as a system designed to support and manage company operations. Over time, it evolved into a full-featured eCommerce and website development platform, offering powerful tools for businesses to grow online.</p>
                    <p class="text-gray-600 mb-6">What started with a small team of passionate developers has grown into a full-service digital agency serving clients across various industries. Our mission has always been clear: to build websites that are not only visually impressive but also deliver real, measurable results.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Mission & Values -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-[#F7FAFA]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-4">Our Mission &amp; Values</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">The principles that guide everything we do</p>
                <div class="w-20 h-1 bg-teal-500 mx-auto mt-4"></div>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="swc-value-card fade-in">
                    <div class="swc-value-icon"><i class="fas fa-lightbulb"></i></div>
                    <h3 class="text-2xl font-bold text-center text-[#2D3945] mb-4">Innovation</h3>
                    <p class="text-gray-600 text-center">We constantly explore new technologies and creative approaches to deliver cutting-edge solutions that give our clients a competitive edge.</p>
                </div>
                
                <!-- Value 2 -->
                <div class="swc-value-card fade-in">
                    <div class="swc-value-icon"><i class="fas fa-handshake"></i></div>
                    <h3 class="text-2xl font-bold text-center text-[#2D3945] mb-4">Integrity</h3>
                    <p class="text-gray-600 text-center">We believe in transparency, honesty, and doing what's right. Our clients trust us because we deliver on our promises.</p>
                </div>
                
                <!-- Value 3 -->
                <div class="swc-value-card fade-in">
                    <div class="swc-value-icon"><i class="fas fa-heart"></i></div>
                    <h3 class="text-2xl font-bold text-center text-[#2D3945] mb-4">Passion</h3>
                    <p class="text-gray-600 text-center">We love what we do, and it shows in every project. Our enthusiasm fuels our creativity and drives us to exceed expectations.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Our Journey -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-4">Our Journey</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Milestones that shaped who we are today</p>
                <div class="w-20 h-1 bg-teal-500 mx-auto mt-4"></div>
            </div>
            
            <div class="max-w-3xl mx-auto swc-timeline">
                <!-- Timeline Item 1 -->
                <div class="swc-timeline-item fade-in">
                    <div class="swc-timeline-dot">2020</div>
                    <div class="swc-timeline-card">
                        <div class="flex items-center mb-2">
                            <span class="text-teal-600 font-bold mr-2">2020</span>
                            <span class="text-sm text-gray-500">June</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#2D3945] mb-2">SoftWebCo Founded</h3>
                        <p class="text-gray-600">Three passionate developers came together with a vision to create powerful digital tools. The idea began in a small co-working space in Beirut, focused on building systems for companies.</p>
                    </div>
                </div>
                
                <!-- Timeline Item 2 -->
                <div class="swc-timeline-item fade-in">
                    <div class="swc-timeline-dot">2021</div>
                    <div class="swc-timeline-card">
                        <div class="flex items-center mb-2">
                            <span class="text-teal-600 font-bold mr-2">2021</span>
                            <span class="text-sm text-gray-500">March</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#2D3945] mb-2"> First System Launched</h3>
                        <p class="text-gray-600">We completed our first internal system designed to help companies manage operations. While we didn't secure clients at this stage, it laid the foundation for our development standards and goals.</p>
                    </div>
                </div>
                
                <!-- Timeline Item 3 -->
                <div class="swc-timeline-item fade-in">
                    <div class="swc-timeline-dot">2022</div>
                    <div class="swc-timeline-card">
                        <div class="flex items-center mb-2">
                            <span class="text-teal-600 font-bold mr-2">2022</span>
                            <span class="text-sm text-gray-500">September</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#2D3945] mb-2">Team Growth</h3>
                        <p class="text-gray-600">Our team expanded with new developers and designers, allowing us to experiment, improve our platform, and prepare for public launch.</p>
                    </div>
                </div>
                
                <!-- Timeline Item 4 -->
                <div class="swc-timeline-item fade-in">
                    <div class="swc-timeline-dot">2023</div>
                    <div class="swc-timeline-card">
                        <div class="flex items-center mb-2">
                            <span class="text-teal-600 font-bold mr-2">2023</span>
                            <span class="text-sm text-gray-500">January</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#2D3945] mb-2">Platform Refinement</h3>
                        <p class="text-gray-600">We spent the year refining our systems, building tools, and designing interfaces to deliver real value to users. Feedback and testing helped us get closer to launch-ready quality.</p>
                    </div>
                </div>

                <!-- Timeline Item 5 -->
                <div class="swc-timeline-item fade-in">
                    <div class="swc-timeline-dot">2025</div>
                    <div class="swc-timeline-card">
                        <div class="flex items-center mb-2">
                            <span class="text-teal-600 font-bold mr-2">2025</span>
                            <span class="text-sm text-gray-500">Present</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#2D3945] mb-2">SoftWebCo Goes Live as a Selling Website</h3>
                        <p class="text-gray-600">After years of development and preparation, SoftWebCo officially launches as an online platform offering digital products and services. While we haven't landed clients yet, we're excited to begin our journey of selling directly to businesses and users around the world.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="relative overflow-hidden py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-teal-600 to-teal-500 text-white text-center">
        <div class="absolute inset-0 pointer-events-none" style="background-image:url('assets/images/brand-pattern-teal.png');background-size:500px;background-repeat:repeat;opacity:0.12;"></div>
        <div class="max-w-3xl mx-auto relative fade-in">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Let's Build Something Together</h2>
            <p class="text-lg text-teal-50 mb-8">Have a project in mind? We'd love to hear about it.</p>
            <a href="contact" class="inline-block px-8 py-3 bg-white text-teal-700 font-semibold rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-xl">Start a Conversation</a>
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