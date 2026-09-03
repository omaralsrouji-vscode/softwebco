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
                .gradient-bg {
            background: linear-gradient(135deg, #0B202D 0%, #2D3945 100%);
        }
        
        .bundle-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        .bundle-card:hover .feature-icon {
            transform: rotate(10deg) scale(1.1);
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .tab-active {
            border-bottom: 3px solid #0d9488;
            color: #0d9488;
            font-weight: 600;
        }





/******** CTA style **********/


    #cta-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 0;
    }

    #cta-section:hover::before {
        opacity: 1;
    }

    #cta-section > div {
        position: relative;
        z-index: 1;
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
    <section class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 mb-12 lg:mb-0">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Premium Web Solutions <span class="text-teal-400">Made Simple</span>
                    </h1>
                    <p class="text-lg text-gray-300 mb-8 max-w-lg">
                        Choose from our expertly crafted bundles and get everything you need for a stunning online presence at an unbeatable value.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#bundles" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 rounded-lg font-medium transition transform hover:scale-105">
                            Explore Bundles
                        </a>
                    </div>
                </div>
                
                <div class="lg:w-1/2 relative">
                    <div class="relative z-10">
                        <img src="assets/images/site/businessman.webp" 
                             alt="Web Development" 
                             class="rounded-xl shadow-2xl border-4 border-white/10">
                    </div>
                    <div class="absolute -top-6 -left-6 w-64 h-64 bg-teal-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
                    <div class="absolute -bottom-8 -right-8 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
                </div>
            </div>
        </div>
    </section>
    
   <!-- Bundle Tabs -->
<section id="bundles" class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-4">Our Web Solution Bundles</h2>
      <p class="text-xl text-gray-600 max-w-3xl mx-auto">Choose the perfect package that fits your business goals</p>
      <div class="w-20 h-1 bg-teal-500 mx-auto mt-4"></div>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="bundles-container">
      
      <!-- Bundle 1 -->
      <div class="bundle-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300" data-category="basic">
        <div class="p-6 bg-[#0B202D] text-white">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-2xl font-bold">Essential Starter</h3>
              <p class="text-teal-300">Perfect for simple, clean online presence</p>
            </div>
            <div class="text-right">
              <span class="text-3xl font-bold">$600</span>
              <p class="text-sm text-gray-300">one-time</p>
            </div>
          </div>
        </div>
        
        <div class="p-6">
          <ul class="space-y-4 mb-8">
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Up to 3 fully responsive pages</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Professional modern design</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Free hosting for 1 year</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Basic SEO setup and optimization</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Contact or inquiry form integration</p></li>
          </ul>
          <div class="text-center">
            <a href="contact" class="inline-block w-full px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition duration-300">
              Get Started
            </a>
          </div>
        </div>
      </div>
      
      <!-- Bundle 2 -->
      <div class="bundle-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 pulse-animation" data-category="business">
        <div class="p-6 bg-teal-600 text-white">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-2xl font-bold">Business Pro</h3>
              <p class="text-teal-100">Perfect for growing businesses</p>
            </div>
            <div class="text-right">
              <span class="text-3xl font-bold">$1200</span>
              <p class="text-sm text-teal-100">one-time</p>
            </div>
          </div>
        </div>
        
        <div class="p-6">
          <ul class="space-y-4 mb-8">
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Up to 7 professionally designed pages</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Includes a small custom system (like orders, messages, or booking)</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Free hosting for 1 year</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Optimized for SEO and fast loading speed</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Basic admin dashboard or control panel</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">3 months of technical support</p></li>
          </ul>
          <div class="text-center">
            <a href="contact" class="inline-block w-full px-6 py-3 bg-[#0B202D] hover:bg-[#2D3945] text-white font-medium rounded-lg transition duration-300">
              Get Started
            </a>
          </div>
        </div>
      </div>
      
      <!-- Bundle 3 -->
      <div class="bundle-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300" data-category="premium">
        <div class="p-6 bg-[#2D3945] text-white">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-2xl font-bold">Custom Enterprise</h3>
              <p class="text-teal-300">Tailored for advanced systems & big projects</p>
            </div>
            <div class="text-right">
              <span class="text-3xl font-bold">Custom Quote</span>
              <p class="text-sm text-gray-300">contact us for pricing</p>
            </div>
          </div>
        </div>
        
        <div class="p-6">
          <ul class="space-y-4 mb-8">
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Fully customized website or system</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Advanced dashboards, databases, or booking systems</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">E-commerce or API integrations</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Unlimited pages & scalability</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Priority support & maintenance plan</p></li>
            <li class="flex items-start"><div class="flex-shrink-0 mt-1"><div class="flex items-center justify-center h-6 w-6 rounded-full bg-teal-100 text-teal-600 feature-icon"><i class="fas fa-check text-sm"></i></div></div><p class="ml-3 text-gray-600">Built entirely to match your goals</p></li>
          </ul>
          <div class="text-center">
            <a href="contact" class="inline-block w-full px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition duration-300">
              Get a Quote
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

    
    <!-- Features Section -->
    <section id="features" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-[#0B202D] mb-4">Why Choose Our Bundles?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">We combine quality, affordability, and convenience in every package</p>
                <div class="w-20 h-1 bg-teal-500 mx-auto mt-4"></div>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-dollar-sign text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-[#2D3945] mb-4">Cost-Effective</h3>
                    <p class="text-gray-600 text-center">
                        Our bundles offer significant savings compared to purchasing services individually. Get more value for your investment.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-clock text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-[#2D3945] mb-4">Time-Saving</h3>
                    <p class="text-gray-600 text-center">
                        Skip the hassle of coordinating multiple vendors. We handle everything from design to deployment.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-shield-alt text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-[#2D3945] mb-4">Reliable Support</h3>
                    <p class="text-gray-600 text-center">
                        Enjoy peace of mind with our included support periods. We're here to help after your site goes live.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-mobile-alt text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-[#2D3945] mb-4">Mobile-Optimized</h3>
                    <p class="text-gray-600 text-center">
                        Every website we build is fully responsive and performs flawlessly on all devices.
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-search text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-[#2D3945] mb-4">SEO-Ready</h3>
                    <p class="text-gray-600 text-center">
                        Our websites are optimized for search engines right out of the box, helping you get found online.
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-rocket text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center text-[#2D3945] mb-4">Fast Performance</h3>
                    <p class="text-gray-600 text-center">
                        We prioritize speed and performance to ensure your visitors have the best possible experience.
                    </p>
                </div>
            </div>
        </div>
    </section>
    

    
   
    
    <!-- CTA Section -->
<section id="cta-section" class="py-16 gradient-bg text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Transform Your Online Presence?</h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
                Choose one of our bundles or contact us for a custom solution tailored to your specific needs.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#bundles" class="px-8 py-3 bg-white hover:bg-gray-100 text-[#0B202D] font-medium rounded-lg transition duration-300 transform hover:scale-105">
                    View Bundles
                </a>
                <a href="contact" class="px-8 py-3 border border-white text-white hover:bg-white hover:text-[#0B202D] font-medium rounded-lg transition duration-300 transform hover:scale-105">
                    Contact Us
                </a>
            </div>
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