<?php

// Регистрация и подключение скриптов
function hfeed_checker_enqueue_scripts()
{
    if (is_singular() && $post = get_queried_object()) {
        global $post;
        $post_id = $post->ID;

        // Регистрируем скрипт
        if (current_user_can('manage_options')) {
            // Регистрируем скрипт без jQuery
            wp_register_script('hfeed-checker-script', plugin_dir_url(__FILE__) . 'hfeed-checker.js', array(), filemtime(plugin_dir_path(__FILE__) . 'hfeed-checker.js'), true);
            // Проверяем, является ли текущий пользователь администратором 
            $is_admin = current_user_can('manage_options');
            // Передаем данные в JavaScript
            wp_localize_script('hfeed-checker-script', 'hfeed_checker_script_vars', array(
                'is_admin' => $is_admin,
            ));

            // Подключаем скрипт на странице
            wp_enqueue_script('hfeed-checker-script');
        }
    }
}

// Добавляем хук для подключения скриптов
add_action('wp_enqueue_scripts', 'hfeed_checker_enqueue_scripts');
