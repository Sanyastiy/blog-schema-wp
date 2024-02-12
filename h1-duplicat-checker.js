
if (h1_duplicat_checker_script_vars.is_headline_name_custom) {
    document.addEventListener('DOMContentLoaded', function () {
        // Ищем все H1
        var h1Tags = document.querySelectorAll('h1');

        // Флаг для отслеживания наличия корректного H1 с itemprop="headline name"
        var foundCorrectH1 = false;

        // Функция для качественного вывода справочной информации
        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return text.replace(/[&<>"']/g, function (m) { return map[m]; });
        }

        if (h1_duplicat_checker_script_vars.is_admin) {
            // Для каждого H1 проверка
            h1Tags.forEach(function (h1) {
                // Проверяем, есть ли у текущего тега атрибут itemprop="headline name"
                if (h1.getAttribute('itemprop') === 'headline name') {
                    // Если такой тег уже найден, устанавливаем флаг, что это неправильный H1
                    if (foundCorrectH1) {
                        h1.style.backgroundColor = 'orange';
                        var message = document.createElement('div');
                        message.style.color = 'orange';
                        message.innerHTML = 'Code:1 For Admin: There is more than one correct H1 with itemprop="headline name" on the page. Please, fix it.';
                        message.innerHTML += '<br>Post ID=' + h1_duplicat_checker_script_vars.post_id;
                        h1.parentNode.insertBefore(message, h1.nextSibling);
                    }
                } else {
                    // Если у текущего тега нет атрибута itemprop="headline name", это неправильный H1
                    h1.style.backgroundColor = 'darkred';
                    var message = document.createElement('div');
                    message.style.color = 'darkred';
                    message.innerHTML = 'Code:2 For Admin: This H1 does not have itemprop="headline name". Please, remove it.';
                    message.innerHTML += '<br>Probably need to make changes to current the_title() function in theme: <br>';
                    message.innerHTML += '<pre>' + escapeHtml('<?php global $post; $post_id = $post->ID; if ($post_id != ') + h1_duplicat_checker_script_vars.post_id + escapeHtml(') {  the_title("<h1 class="entry-title">", "</h1>"); } ?>');
                    message.innerHTML += '</pre>';

                    h1.parentNode.insertBefore(message, h1.nextSibling);
                }
            });
        }
    });

} else if (!h1_duplicat_checker_script_vars.is_headline_name_custom) {

    document.addEventListener('DOMContentLoaded', function () {
        // Ищем все H1
        var h1Tags = document.querySelectorAll('h1');

        // Переменная для отслеживания первого не пустого h1
        var firstNotEmptyH1Found = false;

        // Для каждого H1 проверка, если пустой, то выделение
        h1Tags.forEach(function (h1) {
            // Проверяем, является ли тег пустым
            var isEmptyH1 = (h1.innerHTML.trim() === '');

            // Если админ, выделяем пустые h1 красным и выводим сообщение
            if (h1_duplicat_checker_script_vars.is_admin && isEmptyH1) {
                var message = document.createElement('div');
                message.style.color = 'darkred';
                message.style.fontSize = '18px';
                message.textContent = 'Code:3 For Admin: There is/are empty H1 duplicating tag on page, probably called by the_title(), change it (remove <h1> inside of the_title()) in theme files';
                message.innerHTML += '<br>Probably need to make changes to current the_title() function in theme: <br>';
                message.innerHTML += '<pre>' + escapeHtml('<?php global $post; $post_id = $post->ID; if ($post_id != ') + h1_duplicat_checker_script_vars.post_id + escapeHtml(') {  the_title("<h1 class="entry-title">", "</h1>"); } ?>');
                message.innerHTML += '</pre>';
                h1.parentNode.insertBefore(message, h1.nextSibling);

                // Проверяем ширину и высоту тега
                var computedStyle = window.getComputedStyle(h1);
                var width = parseFloat(computedStyle.getPropertyValue('width'));
                var height = parseFloat(computedStyle.getPropertyValue('height'));

                // Если ширина или высота равны 0, применяем стили
                if (width === 0) {
                    h1.style.width = '100px';
                }
                if (height === 0) {
                    h1.style.height = '20px';
                }
                // Задаем красный фон
                h1.style.backgroundColor = 'darkred';
            }

            // Если не пустой h1 и админ, выделяем не пустой h1 оранжевым цветом
            if (h1_duplicat_checker_script_vars.is_admin && !isEmptyH1) {

                // Флаг для отслеживания наличия корректного H1 с itemprop="headline name"
                var foundCorrectH1 = false;

                if (h1.getAttribute('itemprop') === 'headline name') {
                    // Если такой тег уже найден, устанавливаем флаг, что это неправильный H1
                    if (foundCorrectH1) {
                        // Если это первый не пустой h1, пропускаем его
                        if (!firstNotEmptyH1Found) {
                            firstNotEmptyH1Found = true;
                            return;
                        }

                        // Выделяем не пустой h1 оранжевым цветом
                        h1.style.backgroundColor = 'orange';
                        // Выводим сообщение для администратора
                        var message = document.createElement('div');
                        message.style.color = 'orange';
                        message.innerHTML = 'Code:4 For Admin: There is/are not empty H1 duplicating tag on page, probably called by get_the_title(), change it to the_title() in theme files';
                        message.innerHTML += '<br>Post ID=' + h1_duplicat_checker_script_vars.post_id;
                        h1.parentNode.insertBefore(message, h1.nextSibling);
                    }
                }
                else {
                    // Если у текущего тега нет атрибута itemprop="headline name", это неправильный H1
                    h1.style.backgroundColor = 'darkred';
                    var message = document.createElement('div');
                    message.style.color = 'darkred';
                    message.innerHTML = 'Code:5 For Admin: This H1 does not have itemprop="headline name". Please, remove it.';
                    message.innerHTML += '<br>Post ID=' + h1_duplicat_checker_script_vars.post_id;
                    h1.parentNode.insertBefore(message, h1.nextSibling);
                }
            }
        });
    });
}