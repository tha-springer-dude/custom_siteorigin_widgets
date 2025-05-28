<?php
if (!defined('ABSPATH')) {
    exit;
}

// 🧠 Determine how many items to show based on context
if (is_product()) {
    $count = 2; // WooCommerce product page
} elseif (is_single() || is_home() || is_archive()) {
    $count = 4; // Blog post, archive, blog home
} else {
    $count = 3; // Fallback
}

// 🔹 Show Random Locations
echo '<h3 class="widget-title">' . pll__('Interessante Orte', 'taminotravel') . '</h3>';
echo '<div class="linked-activities-wrapper">';

$random_orte = get_posts([
    'post_type'      => 'tamino_travel_orte',
    'posts_per_page' => $count,
    'orderby'        => 'rand',
    'post_status'    => 'publish',
]);

foreach ($random_orte as $ort) {
    $title   = get_the_title($ort->ID);
    $excerpt = get_the_excerpt($ort->ID);
    $url     = get_permalink($ort->ID);
    $thumb   = get_the_post_thumbnail($ort->ID, 'thumbnail');

    echo '<div class="linked-activity-card">';
    echo '  <h4><a href="' . esc_url($url) . '">' . esc_html($title) . '</a></h4>';
    echo '  <div class="activity-thumb">' . $thumb . '</div>';
    echo '  <p>' . esc_html(wp_trim_words($excerpt, 20)) . '</p>';
    echo '  <a href="' . esc_url($url) . '">' . pll__('Weiterlesen', 'taminotravel') . '</a>';
    echo '</div>';
}
echo '</div>';

// 🔹 Show Random Activities
echo '<h3 class="widget-title">' . pll__('Beliebte Aktivitäten', 'taminotravel') . '</h3>';
echo '<div class="linked-activities-wrapper">';

$random_activities = get_posts([
    'post_type'      => 'tamino_tr_activities',
    'posts_per_page' => $count,
    'orderby'        => 'rand',
    'post_status'    => 'publish',
]);

foreach ($random_activities as $activity) {
    $title   = get_the_title($activity->ID);
    $excerpt = get_the_excerpt($activity->ID);
    $url     = get_permalink($activity->ID);
    $thumb   = get_the_post_thumbnail($activity->ID, 'thumbnail');

    echo '<div class="linked-activity-card">';
    echo '  <h4><a href="' . esc_url($url) . '">' . esc_html($title) . '</a></h4>';
    echo '  <div class="activity-thumb">' . $thumb . '</div>';
    echo '  <p>' . esc_html(wp_trim_words($excerpt, 20)) . '</p>';
    echo '  <a href="' . esc_url($url) . '">' . pll__('Weiterlesen', 'taminotravel') . '</a>';
    echo '</div>';
}
echo '</div>';
?>
