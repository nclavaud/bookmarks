(function($) {
    function getCurrentTabUrl(callback) {
        var queryInfo = {
            active: true,
            currentWindow: true
        };

        chrome.tabs.query(queryInfo, function(tabs) {
            var tab = tabs[0];
            var url = tab.url;
            callback(url);
        });
    }

    function message(string) {
        $('#msg').html(string);
    }

    document.addEventListener('DOMContentLoaded', function() {
        getCurrentTabUrl(function(url) {
            $('#url').attr('value', url);
            message('');
            $('#bookmark-form').show();
        });

        $('#bookmark-form').on('submit', function (event) {
            event.preventDefault();
            var form = $(this);
            $('#submit').attr('disabled', 'disabled');
            $.post(
                form.attr('action'),
                form.serialize(),
                function(data, textStatus, jqXHR) {
                    form.hide();
                    message('✓&nbsp;Bookmark&nbsp;added!');
                },
                'json'
            );
        });

        $('#close').on('click', function(event) {
            event.preventDefault();
            window.close();
        });
    });
})(jQuery);
