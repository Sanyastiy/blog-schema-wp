<?php

// Указать здесь поля, ('имя поля',это переменная типа boolean? не обязательно = нет)
$fields_array = array(
    array('schema_fields_checkbox', true, 'Enable Schema markup?'),
    array('headline_name_checkbox', true, 'Use custom "headline name"? <br><i>(post title by default)</i>'),
    array('headline_name_field', false, 'Set custom "headline name" <br><i>(ex Seven Yachts Options)</i>'),

    array('schema_publisher_name', false, 'Publisher itemprop="publisher name" value <br><i>(ex Seven Yachts - Yacht Charter)</i>'),
    array('schema_publisher_link', false, 'Publisher author url value <br><i>(ex https://www.sevenyachts.ae/)</i>'),
    array('schema_publisher_logo_alt', false, 'Publisher logo alt value <br><i>(ex Seven Yachts - Yacht Charter)</i>'),
    array('schema_publisher_logo_source_url', false, 'Publisher logo source url <br><i>(ex https://www.sevenyachts.ae/assets/images/logo.svg)</i>'),
    array('schema_publisher_legalName', false, 'Publisher legalName value <br><i>(ex Seven Yachts - Yacht Charter)</i>'),
    array('schema_publisher_streetAddress', false, 'Publisher streetAddress value <br><i>(ex D-Marin Dubai Harbour Marina)</i>'),
    array('schema_publisher_addressLocality', false, 'Publisher addressLocality value <br><i>(ex Dubai International Marine Club)</i>'),

    array('schema_author_name', false, 'Publisher author name <br><i>(ex Kasper Jakobsen)</i>'),
    array('schema_author_telephone', false, 'Publisher author telephone <br><i>(ex +971 56 614 0604)</i>'),
    
    array('schema_publisher_google_map', false, 'Publisher Google My Business map <br><i>(ex https://www.google.com/maps/embed/v1/place?key=AIzaSyD8oKMPow9C_THn8XxppGc6Y4R1pMf1Bpc&amp;q=place_id:ChIJuyfBJSNCXz4RBFpzEvnU0o4)</i>'),
);

// Функция для добавления метабокса
function add_custom_meta_box()
{
    add_meta_box(
        'schema-fields-meta-box', // Идентификатор метабокса
        'Schema Fields',   // Заголовок метабокса
        'render_schema_fields_meta_box', // Функция для отображения полей метабокса
        'post',  // Тип записи, к которой привязывается метабокс
        'high', // Место на странице редактирования записи
        'default' // Приоритет
    );
}

// Функция для отображения полей метабокса
function render_schema_fields_meta_box($post)
{
    global $fields_array;
    $post_id = $post->ID;

    // Вывод HTML полей метабокса
    echo '<input type="hidden" name="custom_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';

    foreach ($fields_array as $field) {
        $meta = get_post_meta($post_id, $field[0], true);
        echo '<p>';
        echo '<label for="' . $field[0] . '">' . $field[2] . '</label><br />';

        if ($field[1]) {
            // Если тип поля - чекбокс
            $checked = ($meta == 'on') ? 'checked' : '';
            echo '<input type="checkbox" name="' . $field[0] . '" id="' . $field[0] . '" ' . $checked . '>';
        } else {
            // Если тип поля - текстовое поле
            echo '<input style="width:100%" type="text" name="' . $field[0] . '" id="' . $field[0] . '" value="' . esc_attr($meta) . '">';
        }

        echo '</p>';
    }
}


// Функция для сохранения данных метабокса
function save_custom_meta_box($post_id)
{
    global $fields_array;

    // Проверка наличия права на редактирование
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Проверка наличия nonce
    if (!isset($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Сохранение данных метабокса
    foreach ($fields_array as $field) {
        $meta_key = $field[0];
        $new_meta_value = (isset($_POST[$meta_key]) ? sanitize_text_field($_POST[$meta_key]) : '');

        // Если тип поля - чекбокс, преобразуем значение
        if ($field[1]) {
            $new_meta_value = ($_POST[$meta_key] == 'on') ? 'on' : 'off';
        }

        update_post_meta($post_id, $meta_key, $new_meta_value);
    }
}

// Добавление хуков для метабокса
add_action('add_meta_boxes', 'add_custom_meta_box');
add_action('save_post', 'save_custom_meta_box');
