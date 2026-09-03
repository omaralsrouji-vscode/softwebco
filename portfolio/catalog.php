<?php
$programs = require dirname(__DIR__) . '/programs/catalog.php';

$portfolio = [
    'client-service-portal' => [
        'slug' => 'client-service-portal',
        'title' => 'Client Service Portal',
        'category' => 'Web Application',
        'description' => 'A PHP/MySQL client portal with login, role-based access, service request management and an administration dashboard.',
        'image' => 'assets/images/site/businessman.webp',
        'preview_url' => 'portfolio/live/client-service-portal/index.html',
        'details_url' => 'portfolio-view?project=client-service-portal',
        'tags' => ['PHP', 'MySQL', 'Roles', 'Service Requests'],
        'live' => true,
    ],
    'softwebco' => [
        'slug' => 'softwebco',
        'title' => 'Softwebco Website',
        'category' => 'Company Website',
        'description' => 'Softwebco’s public website, programs catalog, blog, contact flow and administration system.',
        'image' => 'assets/images/brand-pattern-teal.png',
        'preview_url' => 'index',
        'details_url' => 'portfolio-view?project=softwebco',
        'tags' => ['PHP', 'MySQL', 'JavaScript', 'Responsive'],
        'live' => true,
    ],
];

foreach ($programs as $slug => $program) {
    if ($slug === 'portfolio') { continue; }
    $portfolio[$slug] = [
        'slug' => $slug,
        'title' => $program['title'],
        'category' => $program['category'],
        'description' => $program['description'],
        'image' => $program['image'],
        'preview_url' => $program['route'],
        'details_url' => $program['route'],
        'tags' => $program['tags'],
        'live' => false,
    ];
}

return $portfolio;
