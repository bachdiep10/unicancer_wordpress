<?php
if (PHP_SAPI !== 'cli') {
    exit(1);
}

require '/var/www/unicancercenter.com/wp-load.php';

$postTypes = ['page', 'post', 'doctor', 'cancer', 'treatment', 'patient_story', 'special_topic', 'home_slide'];
$rows = [];
$duplicates = [];

foreach ($postTypes as $postType) {
    $posts = get_posts([
        'post_type' => $postType,
        'post_status' => ['publish', 'draft', 'pending', 'private', 'future'],
        'posts_per_page' => -1,
        'orderby' => 'ID',
        'order' => 'ASC',
        'suppress_filters' => false,
    ]);

    $seen = [];
    foreach ($posts as $post) {
        $language = function_exists('pll_get_post_language') ? (pll_get_post_language($post->ID, 'slug') ?: '') : '';
        $title = trim(wp_strip_all_tags($post->post_title));
        $titleForSlug = trim(preg_split('/\s*[|｜]\s*/u', $title, 2)[0] ?? $title);
        $titleSlug = sanitize_title($titleForSlug);
        $key = $postType . ':' . $titleSlug;
        $seen[$key][] = $post->ID;
        $translations = function_exists('pll_get_post_translations') ? pll_get_post_translations($post->ID) : [];

        $rows[] = [
            'id' => $post->ID,
            'type' => $postType,
            'status' => $post->post_status,
            'language' => $language,
            'title' => $title,
            'current_slug' => $post->post_name,
            'title_slug' => $titleSlug,
            'url' => get_permalink($post->ID),
            'translations' => $translations,
        ];
    }

    foreach ($seen as $key => $ids) {
        if (count($ids) > 1) {
            $duplicates[$key] = $ids;
        }
    }
}

$terms = [];
foreach (get_taxonomies(['public' => true], 'names') as $taxonomy) {
    $taxTerms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    if (is_wp_error($taxTerms)) {
        continue;
    }
    foreach ($taxTerms as $term) {
        $language = function_exists('pll_get_term_language') ? (pll_get_term_language($term->term_id, 'slug') ?: '') : '';
        $terms[] = [
            'id' => $term->term_id,
            'taxonomy' => $taxonomy,
            'language' => $language,
            'name' => $term->name,
            'slug' => $term->slug,
            'title_slug' => sanitize_title($term->name),
        ];
    }
}

$result = [
    'generated_at' => gmdate('c'),
    'home' => (int) get_option('page_on_front'),
    'posts_page' => (int) get_option('page_for_posts'),
    'post_count' => count($rows),
    'term_count' => count($terms),
    'duplicates' => $duplicates,
    'posts' => $rows,
    'terms' => $terms,
];

echo wp_json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
