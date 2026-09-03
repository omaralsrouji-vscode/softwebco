<?php
require_once __DIR__ . '/WebDesign.php';
require_once __DIR__ . '/config.php';

$design = new WebDesign();
$blogProfileImage = SWC_BLOG_AUTHOR_IMAGE;

$categories = [];
$categoriesResult = $conn->query("SELECT id, name, slug FROM categories ORDER BY id ASC");
if ($categoriesResult) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

$posts = [];
$postsResult = $conn->query("SELECT bp.*, c.name AS category_name, c.slug AS category_slug
                            FROM blog_posts bp
                            LEFT JOIN categories c ON bp.category_id = c.id
                            ORDER BY bp.post_date DESC, bp.id DESC");
if ($postsResult) {
    while ($row = $postsResult->fetch_assoc()) {
        $posts[] = $row;
    }
}

function blog_e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $design->GenerateHeadTag1(); ?>
    <title>Blogs - Softwebco</title>
    <style>
        /* Keep the original Softwebco blog design while fixing phone/tablet layout. */
        html, body { overflow-x: hidden; }

        .blog-category-nav {
            top: 80px !important;
            z-index: 45;
            padding-top: 7px;
        }

        /* Keep breathing room below the fixed category bar while content scrolls. */
        .blog-category-nav::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -10px;
            height: 10px;
            background: #f8f9fa;
            pointer-events: none;
        }

        .blog-category-list {
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x proximity;
            overscroll-behavior-x: contain;
        }

        .blog-category-item { scroll-snap-align: start; }

        .blog-category-link.active {
            color: #14b8a6;
            border-bottom-color: #14b8a6;
        }

        .blog-container {
            padding-top: 160px !important;
        }

        .blog-card {
            width: 100%;
            min-width: 0;
        }

        .blog-card-title,
        .blog-card-text,
        .blog-author-name {
            overflow-wrap: anywhere;
        }

        .blog-author img {
            background: #0B202D;
        }

        .blog-read-btn {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
        }

        .blog-empty-state {
            grid-column: 1 / -1;
            width: 100%;
            padding: 4rem 1.25rem;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 991px) {
            .blog-container {
                max-width: 760px;
            }
        }

        @media (max-width: 767px) {
            .blog-category-nav {
                top: 80px !important;
                padding-top: 6px;
            }

            .blog-category-nav::after {
                bottom: -8px;
                height: 8px;
            }

            .blog-category-nav .container {
                padding-inline: 10px !important;
            }

            .blog-category-list {
                scrollbar-width: none;
                gap: 2px;
            }

            .blog-category-list::-webkit-scrollbar { display: none; }

            .blog-category-link {
                padding: 12px 13px !important;
                font-size: .8rem !important;
            }

            .blog-container {
                padding: 154px 14px 3.25rem !important;
            }

            .blog-row {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 1.15rem !important;
                margin-bottom: 1.5rem !important;
            }

            .blog-col {
                display: flex !important;
                width: 100%;
                min-width: 0;
            }

            .blog-card {
                border-radius: 14px !important;
                transform: none !important;
            }

            .blog-card-image {
                width: 100% !important;
                height: auto !important;
                aspect-ratio: 1 / 1 !important;
            }

            .blog-card-category {
                top: 12px !important;
                left: 12px !important;
                max-width: calc(100% - 24px);
                padding: 6px 12px !important;
                font-size: .7rem !important;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .blog-card-date {
                right: 12px !important;
                bottom: 12px !important;
                font-size: .68rem !important;
            }

            .blog-card-body {
                padding: 1.1rem !important;
            }

            .blog-card-title {
                font-size: 1.08rem !important;
                margin-bottom: .7rem !important;
                line-height: 1.35 !important;
            }

            .blog-card-text {
                font-size: .9rem;
                line-height: 1.6 !important;
                margin-bottom: 1rem !important;
                -webkit-line-clamp: 4 !important;
            }

            .blog-card-footer {
                gap: .75rem;
                padding-top: .9rem !important;
            }

            .blog-author {
                min-width: 0;
                flex: 1 1 auto;
            }

            .blog-author img {
                width: 38px !important;
                height: 38px !important;
                flex: 0 0 38px;
            }

            .blog-author-name {
                font-size: .82rem !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 145px;
            }

            .blog-read-time { font-size: .73rem !important; }

            .blog-read-btn {
                padding: 8px 14px !important;
                font-size: .8rem !important;
                white-space: nowrap;
                flex: 0 0 auto;
            }
        }

        @media (max-width: 430px) {
            .blog-container {
                padding-inline: 10px !important;
            }

            .blog-card-footer {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            .blog-author-name { max-width: none; }

            .blog-read-btn {
                width: 100%;
                align-self: stretch !important;
            }
        }

        @media (hover: none) {
            .blog-card:hover {
                transform: none !important;
                box-shadow: var(--blog-card-shadow) !important;
            }

            .blog-card:hover .blog-card-image img {
                transform: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="cursor-follower"></div>
    <div class="loading-screen"><div class="loading-text">Loading..</div></div>

    <?php $design->ShowNavbar1(); ?>

    <nav class="blog-category-nav" aria-label="Blog categories">
        <div class="container">
            <ul class="blog-category-list">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $index => $category): ?>
                        <li class="blog-category-item">
                            <a href="#"
                               class="blog-category-link<?= $index === 0 ? ' active' : '' ?><?= swc_text_is_arabic($category['name']) ? ' swc-arabic-text' : '' ?>"
                               data-category="<?= blog_e($category['slug']) ?>"<?= swc_text_language_attributes($category['name']) ?>>
                                <?= blog_e($category['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="blog-category-item">
                        <a href="#" class="blog-category-link active" data-category="all">All Blogs</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="blog-container">
        <div class="blog-row" id="blogPostsContainer">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post):
                    $formattedDate = !empty($post['post_date']) ? date('M d, Y', strtotime($post['post_date'])) : '';
                    $categoryName = $post['category_name'] ?: 'Uncategorized';
                    $categorySlug = $post['category_slug'] ?: 'uncategorized';
                    $titleArabic = swc_text_is_arabic($post['title']);
                    $excerptArabic = swc_text_is_arabic($post['excerpt']);
                    $authorName = $post['author_name'] ?: 'Softwebco Team';
                    $authorArabic = swc_text_is_arabic($authorName);
                    $categoryArabic = swc_text_is_arabic($categoryName);
                ?>
                    <div class="blog-col" data-category="<?= blog_e($categorySlug) ?>">
                        <article class="blog-card">
                            <div class="blog-card-image">
                                <img src="<?= blog_e(swc_blog_image_path($post['image_url'])) ?>"
                                     alt="<?= blog_e($post['title']) ?>"
                                     loading="lazy">
                                <span class="blog-card-category<?= $categoryArabic ? ' swc-arabic-text' : '' ?>"<?= swc_text_language_attributes($categoryName) ?>><?= blog_e($categoryName) ?></span>
                                <div class="blog-card-date"><?= blog_e(strtoupper($formattedDate)) ?></div>
                            </div>

                            <div class="blog-card-body">
                                <h2 class="blog-card-title<?= $titleArabic ? ' swc-arabic-text' : '' ?>"<?= swc_text_language_attributes($post['title']) ?>><?= blog_e($post['title']) ?></h2>
                                <p class="blog-card-text<?= $excerptArabic ? ' swc-arabic-text' : '' ?>"<?= swc_text_language_attributes($post['excerpt']) ?>><?= blog_e($post['excerpt']) ?></p>

                                <div class="blog-card-footer">
                                    <div class="blog-author">
                                        <img src="<?= blog_e($blogProfileImage) ?>"
                                             alt="Softwebco"
                                             loading="lazy">
                                        <div>
                                            <div class="blog-author-name<?= $authorArabic ? ' swc-arabic-text' : '' ?>"<?= swc_text_language_attributes($authorName) ?>><?= blog_e($authorName) ?></div>
                                            <div class="blog-read-time"><?= blog_e($post['read_time']) ?></div>
                                        </div>
                                    </div>

                                    <a class="blog-read-btn" href="blog-detail?id=<?= (int)$post['id'] ?>">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="blog-empty-state">No blog posts found.</div>
            <?php endif; ?>
        </div>
    </main>

    <?php $design->Showfooter(); ?>

    <button id="backToTop" class="fixed bottom-8 right-8 bg-teal-600 text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-teal-700" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/site.js?v=<?= rawurlencode(trim((string)@file_get_contents(__DIR__ . '/VERSION'))) ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryLinks = document.querySelectorAll('.blog-category-link');
            const blogPosts = document.querySelectorAll('.blog-col');

            categoryLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    categoryLinks.forEach(function (item) { item.classList.remove('active'); });
                    this.classList.add('active');

                    const category = this.dataset.category;
                    blogPosts.forEach(function (post) {
                        post.style.display = (category === 'all' || post.dataset.category === category) ? '' : 'none';
                    });
                });
            });
        });
    </script>
</body>
</html>
