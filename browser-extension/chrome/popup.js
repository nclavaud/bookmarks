(function() {
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
        document.getElementById('msg').textContent = string;
        document.getElementById('url').value = string;
    }

    document.addEventListener('DOMContentLoaded', function() {
        getCurrentTabUrl(function(url) {
            message(url);
        });
    });
})();
