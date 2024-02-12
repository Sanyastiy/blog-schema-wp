<?php

class Schema_Markup_Content_Class
{
    public function __construct()
    {
        // Нет необходимости в явном добавлении хука, так как это делается внутри конструктора класса
    }

    public function add_schema_markup_to_content($content)
    {
        // Получаем данные о статье
        $post_id = get_the_ID();
        $is_custom_headline_name = get_post_meta($post_id, 'headline_name_checkbox', true);
        $custom_headline_name = get_post_meta($post_id, 'headline_name_field', true);
        $post_title = ($is_custom_headline_name == "on") ? $custom_headline_name :  get_the_title($post_id);
        $post_date = get_the_date('c', $post_id);
        $post_modified_date = get_the_modified_date('c', $post_id);
        $schema_publisher_name = get_post_meta($post_id, 'schema_publisher_name', true); // Получаем имя автора из пользовательского поля

        $schema_publisher_link = get_post_meta($post_id, 'schema_publisher_link', true);
        $schema_publisher_logo_alt = get_post_meta($post_id, 'schema_publisher_logo_alt', true);
        $schema_publisher_logo_source_url = get_post_meta($post_id, 'schema_publisher_logo_source_url', true);
        $schema_publisher_legalName = get_post_meta($post_id, 'schema_publisher_legalName', true);
        $schema_publisher_streetAddress = get_post_meta($post_id, 'schema_publisher_streetAddress', true);
        $schema_publisher_addressLocality = get_post_meta($post_id, 'schema_publisher_addressLocality', true);

        $schema_author_name = get_post_meta($post_id, 'schema_author_name', true); // Получаем имя автора из пользовательского поля
        $schema_author_telephone = get_post_meta($post_id, 'schema_author_telephone', true); // Получаем имя автора из пользовательского поля

        $schema_publisher_google_map = get_post_meta($post_id, 'schema_publisher_google_map', true); // Получаем имя автора из пользовательского поля

        $post_content = $content;

        // Добавляем Schema разметку к контенту статьи
        $schema_markup = '
<div itemscope itemtype="http://schema.org/NewsArticle">
<h1 class="test good" itemprop="headline name">' . $post_title . '</h1>
    <meta itemprop="datePublished" content="' . date('F j, Y', strtotime($post_date)) . '"/>
    <meta itemprop="dateModified" content="' . date('F j, Y', strtotime($post_modified_date)) . '"/>

    <p>
	<span itemprop="publisher" itemref="publisherLogo" itemscope itemtype="http://schema.org/Organization">
			<span itemprop="name">' . $schema_publisher_name . '</span>
		</span></p>

    <div itemprop="articleBody">' . $post_content . '</div>


    <div itemprop="sourceOrganization author" itemscope itemtype="http://schema.org/LocalBusiness">
	
		<a href="' . $schema_publisher_link . '" itemprop="url" rel="nofollow" 
		style="float: right; margin-top: 20px; margin-left: 10px; display:inline-block !important">
		<span id="publisherLogo" itemprop="logo image" itemscope itemtype="http://schema.org/ImageObject">
		<img alt="' . $schema_publisher_logo_alt . '" itemprop="url" 
		src="' . $schema_publisher_logo_source_url . '" 
		style="max-height: 100px; width: 120px; max-width: 200px; display:inline-block !important"></span>
		<meta content="0" itemprop="priceRange"></a>
		
		<p>Contact Information:</p>
		
		<p itemprop="legalName">' . $schema_publisher_legalName . '</p>
		
		<p itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">' . $schema_publisher_streetAddress . '</span><br>
		<span itemprop="addressLocality">' . $schema_publisher_addressLocality . '</span>, <span itemprop="postalCode">00000</span><br>
		United Arab Emirates</p>
		
		<p><span itemprop="name">' . $schema_author_name . '</span><br>
		<span itemprop="telephone">' . $schema_author_telephone . '</span><br>
		<span><a href="' . $schema_publisher_link . '" rel="nofollow">' . $schema_publisher_link . '</a></span><br></p>
		
		<div style="clear: both;">
			<iframe allowfullscreen frameborder="0" height="450" 
			itemprop="hasMap" itemscope itemtype="http://schema.org/Map" 
			src="' . $schema_publisher_google_map . '" 
			style="border: 0; width: 100%"></iframe>
		</div>
	</div>
			<p>Original Source: <a itemprop="mainEntityOfPage" href="' . $schema_publisher_link . '"> <span itemprop="name">' . $schema_publisher_link . '</span> </a> </p>

</div>';

        // Возвращаем обновленный контент с добавленной разметкой
        return $schema_markup;
    }
}


/*
add_filter('the_title', 'custom_modify_title_tag', 10, 2);

function custom_modify_title_tag($title, $id = null, $tag = 'h1')
{
    global $post; // Получаем глобальную переменную $post

    // Получаем заголовок текущего поста
    $current_post_title = $post->post_title;

    // Проверяем, что передан идентификатор поста
    if ($id) {
        // Проверяем, соответствует ли переданный заголовок заголовку текущего поста
        if ($title === $current_post_title) {
            // Формируем HTML-тег в соответствии с переданным значением $tag
            return "<$tag class='test good' itemprop='headline name'>$title</$tag>";
        }
    }
    // Если не удалось определить текущий пост, возвращаем оригинальный заголовок
    return $title;
}
*/

// Функция для замены контента с помощью класса Schema_Markup_Content_Class
function replace_post_content($content)
{
    // Проверяем состояние мета-поля schema_fields_checkbox
    $schema_enabled = get_post_meta(get_the_ID(), 'schema_fields_checkbox', true);

    // Если мета-поле выключено, возвращаем исходный контент без изменений
    if ($schema_enabled != 'on') {
        return $content;
    }

    // Создаем экземпляр класса Schema_Markup_Content_Class
    $Schema_Markup_Content_Class = new Schema_Markup_Content_Class();

    // Вызываем метод add_schema_markup_to_content для добавления схемной разметки к контенту
    $content_with_schema_markup = $Schema_Markup_Content_Class->add_schema_markup_to_content($content);

    // Возвращаем обновленный контент
    return $content_with_schema_markup;
}

// Добавляем фильтр, который будет вызываться при включении шаблона

add_filter('the_content', 'replace_post_content');
