
if (hfeed_checker_script_vars.is_admin) {
    document.addEventListener('DOMContentLoaded', function () {
        // Ищем все H1
        var hfeedElements = document.querySelectorAll(".hfeed");
        var vcardElements = document.querySelectorAll(".vcard");

        hfeedElements.forEach(function (hfeedItem) {
            hfeedItem.style.backgroundColor = '#FED8B1';
            var message = document.createElement('div');
            message.style.color = 'orange';
            message.style.borderBottom = '2px solid red';
            message.innerHTML = 'Code:6 For Admin: Theme added hatom/hfeed class to the block. Please, remove it.';
            hfeedItem.parentNode.insertBefore(message, hfeedItem.nextSibling);
        });
        vcardElements.forEach(function (vcardItem) {
            vcardItem.style.backgroundColor = '#FED8B1';
            var message = document.createElement('div');
            message.style.color = 'orange';
            message.style.borderBottom = '2px solid red';
            message.innerHTML = 'Code:7 For Admin: Plugin added hcard/vcard class to the block. Please, remove it.';
            vcardItem.parentNode.insertBefore(message, vcardItem.nextSibling);
        });
    });
}
