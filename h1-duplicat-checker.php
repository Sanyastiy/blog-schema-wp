<?php

// Регистрация и подключение скриптов
function h1_duplicat_checker_enqueue_scripts()
{
    if (is_singular() && $post = get_queried_object()) {
        global $post;
        $post_id = $post->ID;

        // Регистрируем скрипт
        if (current_user_can('manage_options')) {
            // Регистрируем скрипт без jQuery
            wp_register_script('h1-duplicat-checker-script', plugin_dir_url(__FILE__) . 'h1-duplicat-checker.js', array(), filemtime(plugin_dir_path(__FILE__) . 'h1-duplicat-checker.js'), true);
            // Проверяем, является ли текущий пользователь администратором 
            $is_admin = current_user_can('manage_options');
            $is_headline_name_custom = get_post_meta($post_id, 'headline_name_checkbox', true) == "on" ? true : false;
            // Передаем данные в JavaScript
            wp_localize_script('h1-duplicat-checker-script', 'h1_duplicat_checker_script_vars', array(
                'is_admin' => $is_admin,
                'is_headline_name_custom' => $is_headline_name_custom,
                'post_id' => $post_id
            ));

            // Подключаем скрипт на странице
            wp_enqueue_script('h1-duplicat-checker-script');
        }
    }
}

// Добавляем хук для подключения скриптов
add_action('wp_enqueue_scripts', 'h1_duplicat_checker_enqueue_scripts');
