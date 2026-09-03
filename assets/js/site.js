// Loading screen: dismiss as soon as the DOM is usable.
// Do not wait for remote fonts/icon styles or large images; on localhost those
// resources can be slow or blocked and must never trap the visitor on Loading….
(function () {
    let dismissed = false;

    function dismissLoadingScreen() {
        if (dismissed) return;
        const loadingScreen = document.querySelector('.loading-screen');
        if (!loadingScreen) {
            dismissed = true;
            return;
        }

        dismissed = true;
        loadingScreen.classList.add('is-hidden');
        loadingScreen.setAttribute('aria-hidden', 'true');
        window.setTimeout(function () {
            loadingScreen.style.display = 'none';
        }, 360);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            window.setTimeout(dismissLoadingScreen, 120);
        }, { once: true });
    } else {
        window.setTimeout(dismissLoadingScreen, 0);
    }

    window.addEventListener('load', dismissLoadingScreen, { once: true });
    // Absolute safety net even if another resource/script behaves badly.
    window.setTimeout(dismissLoadingScreen, 2200);
})();
        
    // Mobile menu toggle with slide animation
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');
let menuOpen = false;

if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', function () {
        if (menuOpen) {
            mobileMenu.style.maxHeight = '0px';
        } else {
            mobileMenu.style.maxHeight = mobileMenu.scrollHeight + 'px';
        }
        menuOpen = !menuOpen;
        mobileMenuButton.setAttribute('aria-expanded', menuOpen ? 'true' : 'false');
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 1120 && menuOpen) {
            mobileMenu.style.maxHeight = '0px';
            menuOpen = false;
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
    }, { passive: true });
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const targetId = this.getAttribute('href');
        if (targetId === '#') return;

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });

            // Close mobile menu smoothly if open
            if (window.innerWidth <= 1120 && menuOpen) {
                mobileMenu.style.maxHeight = '0px';
                menuOpen = false;
            }
        }
    });
});

        
        // Cursor behavior is handled centrally by assets/js/cursor.js.

        // Back to top button
        const backToTopButton = document.getElementById('backToTop');
        if (backToTopButton) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.remove('opacity-100', 'visible');
                backToTopButton.classList.add('opacity-0', 'invisible');
            }
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        }
        
      
        
        // Scroll animations
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const fadeInOnScroll = () => {
            fadeElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };
        
        // Initialize elements as invisible
        fadeElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });
        
        // Check on load and scroll
        window.addEventListener('load', fadeInOnScroll);
        window.addEventListener('scroll', fadeInOnScroll);
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (!nav) return;
            if (window.scrollY > 50) {
                nav.classList.add('shadow-xl');
                nav.classList.add('py-2');
            } else {
                nav.classList.remove('shadow-xl');
                nav.classList.remove('py-2');
            }
        });

        
        // Current-page highlighting is rendered server-side by WebDesign.php.

    const cta = document.getElementById('cta-section');

    if (cta) {
    cta.addEventListener('mousemove', (e) => {
        const rect = cta.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        cta.style.setProperty('--x', `${x}px`);
        cta.style.setProperty('--y', `${y}px`);
        cta.style.setProperty('--radial-gradient', `radial-gradient(circle at ${x}px ${y}px, #20bca9, transparent 60%)`);
        cta.style.backgroundImage = `var(--radial-gradient), linear-gradient(to right, #0B202B, #0B202B)`;
    });

    cta.addEventListener('mouseleave', () => {
        cta.style.backgroundImage = `linear-gradient(to right, #0B202B, #0B202B)`; // fallback gradient
    });
    }


    
document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------
    // Fade-in elements on scroll
    // -------------------------------
    const fadeElements = document.querySelectorAll('.feature-icon, .testimonial');

    fadeElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });

    const fadeInOnScroll = () => {
        fadeElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            if (elementTop < windowHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };

    window.addEventListener('load', fadeInOnScroll);
    window.addEventListener('scroll', fadeInOnScroll);

});

///////////////////////////////////////////////////////////////////////////////////

// Blog JavaScript with unique namespace
const BlogApp = (function() {
    'use strict';
    
    // Private variables
    let currentPage = 1;
    const articlesPerPage = 5;
    const totalArticles = 10; // Simulating total articles from server
    
    // DOM Elements
    const blogLoadMoreBtn = document.getElementById('blogLoadMore');
    const blogArticleModal = document.getElementById('blogArticleModal');
    const blogModalTitle = blogArticleModal ? blogArticleModal.querySelector('.blog-modal-title') : null;
    const blogModalCategory = blogArticleModal ? blogArticleModal.querySelector('.blog-modal-category') : null;
    const blogModalDate = blogArticleModal ? blogArticleModal.querySelector('.blog-modal-date') : null;
    const blogModalAuthor = blogArticleModal ? blogArticleModal.querySelector('.blog-modal-author') : null;
    const blogModalImage = blogArticleModal ? blogArticleModal.querySelector('.blog-modal-image') : null;
    const blogModalText = blogArticleModal ? blogArticleModal.querySelector('.blog-modal-text') : null;
    const blogModalClose = blogArticleModal ? blogArticleModal.querySelector('.blog-modal-close') : null;
    
    // Article data (simulating database)
    const blogArticles = {
        1: {
            title: "The Future of Artificial Intelligence in Everyday Life",
            category: "TECHNOLOGY",
            date: "MAY 15, 2023",
            author: "Michael Chen",
            image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            content: `<p>Artificial Intelligence is no longer a futuristic concept but a present-day reality that's transforming how we live, work, and interact with the world around us. From voice assistants that understand natural language to recommendation systems that anticipate our needs, AI has become an integral part of our daily lives.</p>
                      
                      <p>The most significant impact of AI can be seen in healthcare, where machine learning algorithms are helping doctors diagnose diseases earlier and with greater accuracy. AI-powered imaging systems can detect anomalies in medical scans that might be missed by human eyes, potentially saving countless lives through early intervention.</p>
                      
                      <p>In our homes, smart devices are becoming increasingly sophisticated. Beyond simple voice commands, modern AI systems can learn our preferences and routines, automatically adjusting lighting, temperature, and even suggesting recipes based on what's in our refrigerator. This level of personalization was unimaginable just a decade ago.</p>
                      
                      <p>The transportation sector is undergoing a revolution with autonomous vehicles becoming more common. While fully self-driving cars are still in development, many modern vehicles already incorporate AI features like adaptive cruise control, lane-keeping assistance, and automatic emergency braking.</p>
                      
                      <p>As we look to the future, we can expect AI to become even more integrated into our lives. The next decade will likely see advances in personalized education, more efficient energy management, and breakthroughs in scientific research powered by AI's ability to process vast amounts of data and identify patterns invisible to humans.</p>`
        },
        2: {
            title: "Sustainable Design: Building a Better Future",
            category: "DESIGN",
            date: "APRIL 28, 2023",
            author: "Sarah Johnson",
            image: "https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            content: `<p>Sustainable design represents a fundamental shift in how we approach creation and consumption. It's no longer enough for products to be beautiful and functional—they must also be environmentally responsible and socially conscious.</p>
                      
                      <p>The core principles of sustainable design include using renewable materials, minimizing waste throughout the product lifecycle, and creating items that are durable and repairable rather than disposable. Designers are increasingly turning to materials like bamboo, recycled plastics, and biodegradable composites that leave a smaller environmental footprint.</p>
                      
                      <p>One of the most exciting developments in sustainable design is the concept of the circular economy. Instead of the traditional linear model of "take, make, dispose," circular design considers the entire lifecycle of a product. This means designing items that can be easily disassembled, repaired, upgraded, or recycled at the end of their useful life.</p>
                      
                      <p>Architecture and interior design have also embraced sustainability. Green buildings now incorporate features like living walls, rainwater harvesting systems, and smart energy management. These buildings not only reduce environmental impact but often provide healthier, more pleasant spaces for occupants.</p>
                      
                      <p>The movement toward sustainable design represents a hopeful vision for our future—one where human creativity and innovation work in harmony with nature rather than against it. As consumers become more environmentally conscious, sustainable design will continue to evolve from a niche concern to a mainstream expectation.</p>`
        },
        // More articles would follow similar structure
    };
    
    // Initialize the blog
    function init() {
        if (!blogArticleModal || !blogModalClose) return;
        setupEventListeners();
    }
    
    // Set up event listeners
    function setupEventListeners() {
        // Read More buttons
        document.querySelectorAll('.blog-read-btn').forEach(button => {
            button.addEventListener('click', function() {
                const articleId = this.getAttribute('data-id');
                openArticleModal(articleId);
            });
        });
        
        // Load More button
        if (blogLoadMoreBtn) {
            blogLoadMoreBtn.addEventListener('click', loadMoreArticles);
        }
        
        // Modal close button
        blogModalClose.addEventListener('click', closeModal);
        
        // Close modal when clicking outside
        blogArticleModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && blogArticleModal.style.display === 'block') {
                closeModal();
            }
        });
    }
    
    // Open article modal
    function openArticleModal(articleId) {
        const article = blogArticles[articleId];
        
        if (!article) {
            console.error('Article not found:', articleId);
            return;
        }
        
        // Populate modal with article data
        blogModalTitle.textContent = article.title;
        blogModalCategory.textContent = article.category;
        blogModalDate.textContent = article.date;
        blogModalAuthor.textContent = `BY ${article.author}`;
        blogModalImage.src = article.image;
        blogModalImage.alt = article.title;
        blogModalText.innerHTML = article.content;
        
        // Show modal
        blogArticleModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Add animation class
        setTimeout(() => {
            blogArticleModal.querySelector('.blog-modal-content').classList.add('blog-modal-open');
        }, 10);
    }
    
    // Close modal
    function closeModal() {
        blogArticleModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        blogArticleModal.querySelector('.blog-modal-content').classList.remove('blog-modal-open');
    }
    
    // Load more articles (simulated)
    function loadMoreArticles() {
        const button = blogLoadMoreBtn;
        const buttonText = button.querySelector('.blog-load-text');
        const buttonSpinner = button.querySelector('.blog-load-spinner');
        
        // Show loading state
        buttonText.textContent = 'Loading...';
        buttonSpinner.style.display = 'inline-block';
        button.disabled = true;
        
        // Simulate API call delay
        setTimeout(() => {
            currentPage++;
            const hasMoreArticles = (currentPage * articlesPerPage) < totalArticles;
            
            if (hasMoreArticles) {
                // In a real app, this would fetch from an API
                addMockArticles();
                
                buttonText.textContent = 'Load More Articles';
                buttonSpinner.style.display = 'none';
                button.disabled = false;
                
                showNotification('New articles loaded successfully!');
            } else {
                // No more articles
                buttonText.textContent = 'No More Articles';
                buttonSpinner.style.display = 'none';
                button.disabled = true;
                button.style.opacity = '0.6';
                
                showNotification('All articles have been loaded!');
            }
        }, 1500);
    }
    
    // Add mock articles (simulating new content)
    function addMockArticles() {
        const blogRow = document.querySelector('.blog-row');
        if (!blogRow) return;
        
        // Create new article element
        const newArticle = document.createElement('div');
        newArticle.className = 'blog-col';
        newArticle.innerHTML = `
            <div class="blog-card">
                <div class="blog-card-image">
                    <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Edge Computing">
                    <span class="blog-card-category">TECHNOLOGY</span>
                    <div class="blog-card-date">JUNE 10, 2023</div>
                </div>
                <div class="blog-card-body">
                    <h3 class="blog-card-title">The Rise of Edge Computing in Modern Applications</h3>
                    <p class="blog-card-text">Discover how edge computing is revolutionizing data processing and enabling faster, more efficient applications across industries.</p>
                    <div class="blog-card-footer">
                        <div class="blog-author">
                            <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="Robert Kim">
                            <div>
                                <div class="blog-author-name">Robert Kim</div>
                                <div class="blog-read-time">7 min read</div>
                            </div>
                        </div>
                        <button class="blog-read-btn" data-id="6">Read More</button>
                    </div>
                </div>
            </div>
        `;
        
        // Add to container
        blogRow.appendChild(newArticle);
        
        // Re-attach event listener to new button
        newArticle.querySelector('.blog-read-btn').addEventListener('click', function() {
            openArticleModal(this.getAttribute('data-id'));
        });
        
        // Add fade-in animation
        setTimeout(() => {
            newArticle.style.opacity = '0';
            newArticle.style.transform = 'translateY(20px)';
            blogRow.appendChild(newArticle);
            
            // Trigger animation
            setTimeout(() => {
                newArticle.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                newArticle.style.opacity = '1';
                newArticle.style.transform = 'translateY(0)';
            }, 10);
        }, 10);
    }
    
    // Show notification
    function showNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'blog-notification';
        notification.innerHTML = `
            <div class="blog-notification-content">
                <span>${message}</span>
            </div>
        `;
        
        // Add styles if not already added
        if (!document.querySelector('#blog-notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'blog-notification-styles';
            styles.textContent = `
                .blog-notification {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: var(--blog-primary-accent);
                    color: white;
                    padding: 15px 25px;
                    border-radius: 10px;
                    z-index: 1000;
                    animation: blogNotificationSlideIn 0.3s ease-out;
                    box-shadow: 0 5px 15px rgba(32, 188, 169, 0.3);
                    max-width: 350px;
                }
                @keyframes blogNotificationSlideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes blogNotificationSlideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(styles);
        }
        
        // Add to page
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'blogNotificationSlideOut 0.3s ease-out forwards';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Public API
    return {
        init: init,
        openArticleModal: openArticleModal,
        closeModal: closeModal
    };
})();

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', BlogApp.init);
