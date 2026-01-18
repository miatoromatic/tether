# FINAL OVERLAY FIX - Use This HTML

Replace your custom page HTML with this:

```html
<style>
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

.boon { color: #51bbb1; }
.bane { color: #d24b7e; }

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
    <div class="counter" id="tetherCounter">Loading...</div>
    <div class="iframe-container">
        <iframe id="tetherIframe" title="Tether Wiki"></iframe>
    </div>
</div>

<script>
(function() {
    // Function to initialize the tether display
    function initTether() {
        // Find the overlay trigger element (the link that opened this overlay)
        var overlayTrigger = null;

        // Try to get data from the trigger element via XenForo's overlay system
        if (window.parent && window.parent.XF && window.parent.XF.config) {
            // Get the last clicked overlay trigger
            var overlays = window.parent.document.querySelectorAll('.overlay');
            if (overlays.length > 0) {
                var lastOverlay = overlays[overlays.length - 1];
                // Find the trigger element
                var triggers = window.parent.document.querySelectorAll('[data-tether-name]');
                for (var i = triggers.length - 1; i >= 0; i--) {
                    overlayTrigger = triggers[i];
                    break;
                }
            }
        }

        var tetherName = '';
        var boon = 0;
        var bane = 0;

        // Get data from trigger element
        if (overlayTrigger) {
            tetherName = overlayTrigger.getAttribute('data-tether-name') || '';
            boon = parseInt(overlayTrigger.getAttribute('data-tether-boon')) || 0;
            bane = parseInt(overlayTrigger.getAttribute('data-tether-bane')) || 0;
        }

        var counterElement = document.getElementById('tetherCounter');
        var iframeElement = document.getElementById('tetherIframe');

        if (!tetherName) {
            counterElement.innerHTML = '<span class="error-message">Error: No tether data found</span>';
            return;
        }

        // Build wiki URL - using terrarp.com as specified
        var wikiUrl = 'https://terrarp.com/wiki/' + tetherName + '_(BBcode)';

        // Display counter
        counterElement.innerHTML =
            '<span class="boon">Boon:</span> <span class="boon">' + boon + '</span> · ' +
            '<span class="bane">Bane:</span> <span class="bane">' + bane + '</span>';

        // Load wiki
        iframeElement.src = wikiUrl;
    }

    // Wait for DOM to be ready, then initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTether);
    } else {
        initTether();
    }

    // Also listen for XenForo's overlay shown event
    XF.on(document, 'overlay:shown', function() {
        setTimeout(initTether, 100);
    });
})();
</script>
```

This approach:
1. Looks for the data attributes on the link element that triggered the overlay
2. Reads `data-tether-name`, `data-tether-boon`, and `data-tether-bane`
3. Fixed wiki URL to `https://terrarp.com/wiki/`
4. Displays the counter and loads the wiki iframe
