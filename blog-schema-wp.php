<?php
/*
Plugin Name: Schema fields for blog articles
Description: This is simple plugin that adds a group of fields which allows to add Schema markup for Blog posts.
Version: 1.0
Author: Alex Beontop
*/

// Отключение генерации разметки Hatom (все равно может возникнуть из-за классов в теме)
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'wp_generator');

// Отключение генерации разметки Hcard (все равно может возникнуть из-за классов в теме)
remove_action('wp_head', 'rel_canonical');


// База замены текста
require_once('schema-markup-change.php');

// База метабоксов
require_once('meta-boxes.php');

// Проверка H1 заголовочных тегов
require_once('h1-duplicat-checker.php');

// Проверка hfeed
require_once('hfeed-checker.php');
