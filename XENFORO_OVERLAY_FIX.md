# Updated XenForo Custom Page HTML

Use this HTML for your custom page at `/pages/tether/`:

```html
<style>
body {
    background: #101219;
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, Liberation Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
}

.tether-overlay-container {
    background: #101219;
    min-height: 600px;
    padding: 20px;
}

.counter {
    padding: 10px;
    margin: 10px 0;
    color: #474d60;
    font-size: 1.4em;
    font-weight: 600;
    text-align: left;
}

.boon {
    color: #51bbb1;
}

.bane {
    color: #d24b7e;
}

.iframe-container {
    margin: 20px 0;
    border-radius: 8px;
    background: transparent;
    overflow: hidden;
    height: 70vh;
    min-height: 500px;
}

.iframe-container iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
}

.error-message {
    color: #ff6b6b;
    padding: 20px;
    text-align: center;
    font-size: 16px;
}
</style>

<div class="tether-overlay-container">
    <div class="counter" id="tetherCounter">
        Loading...
    </div>
    <div class="iframe-container">
        <iframe id="tetherIframe" title="Tether Wiki"></iframe>
    </div>
</div>

<script>
(function() {
    // Get parameters from URL hash (works in XenForo overlays)
    function getHashParameter(name) {
        var hash = window.location.hash.substring(1); // Remove the # symbol
        var params = {};

        if (hash) {
            var pairs = hash.split('&');
            for (var i = 0; i < pairs.length; i++) {
                var pair = pairs[i].split('=');
                params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
            }
        }

        return params[name] || '';
    }

    // Get parameters from hash
    var tetherName = getHashParameter('name');
    var boon = parseInt(getHashParameter('boon')) || 0;
    var bane = parseInt(getHashParameter('bane')) || 0;

    var counterElement = document.getElementById('tetherCounter');
    var iframeElement = document.getElementById('tetherIframe');

    if (!tetherName) {
        counterElement.innerHTML = '<span class="error-message">Error: No tether specified</span>';
        return;
    }

    // Build wiki URL - note: using terrarp.net based on user's comment
    var wikiUrl = 'https://terrarp.net/wiki/' + tetherName + '_(BBcode)';

    // Display counter with colored values
    counterElement.innerHTML =
        '<span class="boon">Boon:</span> <span class="boon">' + boon + '</span> · ' +
        '<span class="bane">Bane:</span> <span class="bane">' + bane + '</span>';

    // Load wiki in iframe
    iframeElement.src = wikiUrl;
})();
</script>
```

## Key Changes:

1. **Changed from `?` to `#`** - Uses hash parameters instead of query parameters
2. **New `getHashParameter()` function** - Reads from `window.location.hash` instead of `location.search`
3. **Updated wiki domain** - Changed to `terrarp.net` (as you mentioned)
4. **Added padding** - Better spacing in overlay

## This URL format now:
```
/tsbeta/pages/tether/#name=Gondoliers_Mercy&boon=5&bane=1
```

Instead of:
```
/tsbeta/pages/tether/?name=Gondoliers_Mercy&boon=5&bane=1
```

The hash (`#`) parameters are accessible even when the page is loaded in a XenForo overlay!
