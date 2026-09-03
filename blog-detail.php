<?php
include("WebDesign.php");
require('config.php');
$design = new WebDesign();
$blogProfileImage = SWC_BLOG_AUTHOR_IMAGE;

// Database connection is provided by config.php.
// Check if blog ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid blog post ID");
}

$blog_id = intval($_GET['id']);

// Fetch single blog post
$blog_query = "SELECT bp.*, c.name as category_name, c.slug as category_slug
               FROM blog_posts bp 
               LEFT JOIN categories c ON bp.category_id = c.id 
               WHERE bp.id = ?";
$stmt = $conn->prepare($blog_query);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$blog_result = $stmt->get_result();

if ($blog_result->num_rows === 0) {
    die("Blog post not found");
}

$post = $blog_result->fetch_assoc();

$titleArabic = swc_text_is_arabic($post['title']);
$excerptArabic = swc_text_is_arabic($post['excerpt'] ?? '');
$contentArabic = swc_text_is_arabic($post['content'] ?? '');
$authorArabic = swc_text_is_arabic($post['author_name'] ?? '');


// Format date
$formatted_date = date("F j, Y", strtotime($post['post_date']));
$category_name = $post['category_name'] ?: 'Uncategorized';

// Fetch related posts (posts from same category, excluding current post)
$related_query = "SELECT bp.*, c.name as category_name 
                  FROM blog_posts bp 
                  LEFT JOIN categories c ON bp.category_id = c.id 
                  WHERE bp.category_id = ? AND bp.id != ? 
                  ORDER BY bp.post_date DESC 
                  LIMIT 3";
$related_stmt = $conn->prepare($related_query);
$related_stmt->bind_param("ii", $post['category_id'], $blog_id);
$related_stmt->execute();
$related_result = $related_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php
    $design->GenerateHeadTag1();
?>
    <title><?php echo htmlspecialchars($post['title']); ?> - Blog</title>
    <style>
        /* Blog Detail Styles */
        .blog-detail-container {
            padding-top: 120px;
            min-height: 100vh;
            background-color: #f8fafc;
        }

        .blog-detail-header {
            max-width: 800px;
            margin: 0 auto 3rem;
            text-align: center;
        }

        .blog-detail-category {
            display: inline-block;
            background-color: #20bca9;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }

        .blog-detail-title {
            font-size: 3rem;
            font-weight: 800;
            color: #0B202D;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .blog-detail-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            color: #64748b;
            font-size: 15px;
            margin-bottom: 40px;
        }

        .blog-detail-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .blog-detail-meta-item i {
            color: #20bca9;
        }

        .blog-detail-image {
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background: #eef3f4;
        }

        .blog-detail-content {
            max-width: 800px;
            margin: 0 auto 4rem;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .blog-detail-content p {
            font-size: 18px;
            line-height: 1.8;
            color: #334155;
            margin-bottom: 1.5rem;
        }

        .blog-detail-content h2 {
            font-size: 2rem;
            color: #0B202D;
            margin: 2.5rem 0 1rem;
            font-weight: 700;
        }

        .blog-detail-content h3 {
            font-size: 1.5rem;
            color: #1e293b;
            margin: 2rem 0 1rem;
            font-weight: 600;
        }

        .blog-detail-content blockquote {
            border-left: 4px solid #20bca9;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #475569;
            font-size: 1.25rem;
        }

        .blog-detail-author {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            display: flex;
            gap: 2rem;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .blog-detail-author-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f1f5f9;
        }

        .blog-detail-author-info h4 {
            font-size: 1.5rem;
            color: #0B202D;
            margin-bottom: 0.5rem;
        }

        .blog-detail-author-info p {
            color: #64748b;
            line-height: 1.6;
        }

        .related-posts {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 1rem;
        }

        .related-posts h3 {
            font-size: 2rem;
            color: #0B202D;
            text-align: center;
            margin-bottom: 3rem;
            font-weight: 700;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .related-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .related-card img {
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            background: #eef3f4;
        }

        .related-card-content {
            padding: 1.5rem;
        }

        .related-card h4 {
            font-size: 1.25rem;
            color: #0B202D;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .related-card p {
            color: #64748b;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .related-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #94a3b8;
        }

        .blog-detail-actions {
            max-width: 800px;
            margin: 2rem auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-to-blog {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #20bca9;
            font-weight: 600;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: white;
            border: 2px solid #20bca9;
        }

        .back-to-blog:hover {
            background: #20bca9;
            color: white;
            transform: translateX(-5px);
        }

        .blog-detail-container,
        .blog-detail-content,
        .blog-detail-header,
        .blog-detail-author,
        .related-posts,
        .blog-detail-actions {
            min-width: 0;
        }

        .blog-content,
        .blog-detail-content p,
        .blog-detail-content h2,
        .blog-detail-content h3,
        .related-card h4,
        .related-card p {
            overflow-wrap: anywhere;
        }

        .blog-detail-author-img {
            background: #0B202D;
        }

        @media (max-width: 768px) {
            .blog-detail-container {
                padding-top: 104px;
                overflow-x: hidden;
            }

            .blog-detail-header {
                padding: 0 16px;
                margin-bottom: 2rem;
            }

            .blog-detail-category {
                font-size: 11px;
                padding: 6px 12px;
                margin-bottom: 14px;
            }

            .blog-detail-title {
                font-size: clamp(1.7rem, 8vw, 2.2rem);
                line-height: 1.15;
                margin-bottom: 1rem;
            }

            .blog-detail-meta {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px 14px;
                font-size: 12px;
                margin-bottom: 0;
            }

            .blog-detail-meta-item {
                gap: 5px;
            }

            .blog-detail-container > div[style*="max-width: 800px"] {
                padding: 0 12px;
                margin-bottom: 1.5rem !important;
            }

            .blog-detail-image {
                width: 100%;
                height: auto;
                min-height: 0;
                aspect-ratio: 1 / 1;
                border-radius: 10px;
                margin-bottom: 0;
            }

            .blog-detail-content {
                margin: 0 12px 2rem;
                padding: 1.2rem;
                border-radius: 10px;
            }

            .blog-detail-content p {
                font-size: 15.5px;
                line-height: 1.72;
                margin-bottom: 1.15rem;
            }

            .blog-detail-content h2 {
                font-size: 1.45rem;
                line-height: 1.3;
                margin: 1.8rem 0 .8rem;
            }

            .blog-detail-content h3 {
                font-size: 1.2rem;
            }

            .blog-detail-content blockquote {
                padding-left: 1rem;
                font-size: 1rem;
            }

            .blog-detail-author {
                margin: 2rem 12px;
                flex-direction: column;
                text-align: center;
                padding: 1.25rem;
                gap: 1rem;
            }

            .blog-detail-author-img {
                width: 92px;
                height: 92px;
            }

            .blog-detail-author-info h4 {
                font-size: 1.2rem;
            }

            .blog-detail-author-info p {
                font-size: .9rem;
            }

            .related-posts {
                margin: 2.5rem auto;
                padding: 0 12px;
            }

            .related-posts h3 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .related-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 1rem;
            }

            .related-card img {
                height: auto;
                aspect-ratio: 1 / 1;
            }

            .related-card-content {
                padding: 1.1rem;
            }

            .related-card-meta {
                gap: .75rem;
                flex-wrap: wrap;
            }

            .blog-detail-actions {
                margin: 1.5rem 12px 2.5rem;
            }

            .back-to-blog {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 420px) {
            .blog-detail-meta {
                align-items: center;
            }

            .blog-detail-content {
                margin-inline: 8px;
                padding: 1rem;
            }

            .blog-detail-author,
            .blog-detail-actions {
                margin-inline: 8px;
            }

            .related-posts {
                padding-inline: 8px;
            }
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
    
    <div class="blog-detail-container">
        <!-- Blog Header -->
        <div class="blog-detail-header">
            <span class="blog-detail-category<?php echo swc_text_is_arabic($category_name) ? ' swc-arabic-text' : ''; ?>"<?php echo swc_text_language_attributes($category_name); ?>><?php echo htmlspecialchars($category_name, ENT_QUOTES, 'UTF-8'); ?></span>
            <h1 class="blog-detail-title<?php echo $titleArabic ? ' swc-arabic-text' : ''; ?>"<?php echo swc_text_language_attributes($post['title']); ?>><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <div class="blog-detail-meta">
                <div class="blog-detail-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span><?php echo $formatted_date; ?></span>
                </div>
                <div class="blog-detail-meta-item">
                    <i class="fas fa-user"></i>
                    <span class="<?php echo $authorArabic ? 'swc-arabic-text' : ''; ?>"<?php echo swc_text_language_attributes($post['author_name']); ?>><?php echo htmlspecialchars($post['author_name']); ?></span>
                </div>
                <div class="blog-detail-meta-item">
                    <i class="fas fa-clock"></i>
                    <span><?php echo $post['read_time']; ?></span>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        <?php if (!empty($post['image_url'])): ?>
        <div style="max-width: 800px; margin: 0 auto 3rem;">
            <img src="<?php echo htmlspecialchars(swc_blog_image_path($post['image_url'])); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="blog-detail-image">
        </div>
        <?php endif; ?>

        <!-- Blog Content -->
        <div class="blog-detail-content">
            <?php 
            // Display excerpt first if exists
            if (!empty($post['excerpt'])): ?>
                <p class="blog-detail-excerpt<?php echo $excerptArabic ? ' swc-arabic-text' : ''; ?>"<?php echo swc_text_language_attributes($post['excerpt']); ?> style="font-size: 1.25rem; color: #475569; font-weight: 500; border-left: 3px solid #20bca9; padding-left: 1rem; margin-bottom: 2rem;">
                    <?php echo htmlspecialchars($post['excerpt']); ?>
                </p>
            <?php endif; ?>
            
            <!-- Main content - assuming content is stored as HTML -->
            <div class="blog-content<?php echo $contentArabic ? ' swc-arabic-text' : ''; ?>"<?php echo swc_text_language_attributes($post['content']); ?>>
                <?php 
                // If content contains HTML tags, output as is, otherwise treat as plain text
                if (strpos($post['content'], '<') !== false) {
                    echo $post['content'];
                } else {
                    // Convert plain text to HTML paragraphs
                    $paragraphs = preg_split('/\n\s*\n/', $post['content']);
                    foreach ($paragraphs as $paragraph) {
                        if (trim($paragraph) !== '') {
                            echo '<p>' . nl2br(htmlspecialchars(trim($paragraph))) . '</p>';
                        }
                    }
                }
                ?>
            </div>
        </div>

        <!-- Author Section -->
        <div class="blog-detail-author">
            <img src="<?php echo htmlspecialchars($blogProfileImage, ENT_QUOTES, 'UTF-8'); ?>" alt="Softwebco" class="blog-detail-author-img">
            <div class="blog-detail-author-info">
                <h4>Written by <span class="<?php echo $authorArabic ? 'swc-arabic-text' : ''; ?>"<?php echo swc_text_language_attributes($post['author_name']); ?>><?php echo htmlspecialchars($post['author_name']); ?></span></h4>
                <p>
                    Carefully crafted by <?php echo htmlspecialchars($post['author_name']); ?>. Here, we believe in the power of purposeful words—writing not just to inform, but to inspire, connect, and resonate, always with sincerity at the core.
            </p>
            </div>
        </div>

        <!-- Related Posts -->
        <?php if ($related_result->num_rows > 0): ?>
        <div class="related-posts">
            <h3>Related Articles</h3>
            <div class="related-grid">
                <?php while($related_post = $related_result->fetch_assoc()): 
                    $related_date = date("M d, Y", strtotime($related_post['post_date']));
                ?>
                <div class="related-card">
                    <img src="<?php echo htmlspecialchars(swc_blog_image_path($related_post['image_url']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($related_post['title']); ?>">
                    <div class="related-card-content">
                        <h4 class="<?php echo swc_text_is_arabic($related_post['title']) ? 'swc-arabic-text' : ''; ?>"<?php echo swc_text_language_attributes($related_post['title']); ?>><?php echo htmlspecialchars($related_post['title']); ?></h4>
                        <p class="<?php echo swc_text_is_arabic($related_post['excerpt']) ? 'swc-arabic-text' : ''; ?>"<?php echo swc_text_language_attributes($related_post['excerpt']); ?>><?php echo htmlspecialchars(function_exists('mb_substr') ? mb_substr($related_post['excerpt'], 0, 100) : substr($related_post['excerpt'], 0, 100)) . '...'; ?></p>
                        <div class="related-card-meta">
                            <span><?php echo $related_date; ?></span>
                            <a href="blog-detail.php?id=<?php echo $related_post['id']; ?>" class="text-teal-600 font-medium hover:text-teal-700">Read More →</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Back to Blog Button -->
        <div class="blog-detail-actions">
            <a href="javascript:history.back()" class="back-to-blog">
                <i class="fas fa-arrow-left"></i> Back to Blog
            </a>
        </div>
    </div>

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
    
    <script>
        // Update the blog listing page to use this detail page
        document.addEventListener('DOMContentLoaded', function() {
            // If we're on the blog listing page, update the Read More buttons
            const readMoreButtons = document.querySelectorAll('.blog-read-btn');
            if (readMoreButtons.length > 0) {
                readMoreButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const postId = this.getAttribute('data-id');
                        window.location.href = 'blog-detail.php?id=' + postId;
                    });
                });
            }

            // Add smooth scrolling for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href !== '#') {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>

<?php
// Close connections
$stmt->close();
if (isset($related_stmt)) {
    $related_stmt->close();
}
$conn->close();
?>