# Simple Overlay Solution - Copy This Exact HTML

Copy and paste this **ENTIRE** code into your custom page Template HTML:

```html
<div id="tetherContent" style="background: #101219; min-height: 600px; padding: 20px;">
    <div id="tetherCounter" style="padding: 10px; margin: 10px 0; color: #474d60; font-size: 1.4em; font-weight: 600;">
        Loading...
    </div>
    <div id="tetherIframe" style="margin: 20px 0; border-radius: 8px; height: 70vh; min-height: 500px;">
    </div>
</div>

<script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
jQuery(document).ready(function($) {
    // Get URL parameters
    var urlParams = new URLSearchParams(window.location.search);
    var name = urlParams.get('name') || '';
    var boon = urlParams.get('boon') || '0';
    var bane = urlParams.get('bane') || '0';

    console.log('Tether params:', {name: name, boon: boon, bane: bane});

    if (!name) {
        $('#tetherCounter').html('<span style="color:#ff6b6b;">Error: No tether specified</span>');
        return;
    }

    // Build counter HTML
    var counterHtml = '<span style="color: #51bbb1;">Boon:</span> <span style="color: #51bbb1;">' + boon + '</span> · ' +
                      '<span style="color: #d24b7e;">Bane:</span> <span style="color: #d24b7e;">' + bane + '</span>';

    $('#tetherCounter').html(counterHtml);

    // Build and insert iframe
    var wikiUrl = 'https://terrarp.com/wiki/' + name + '_(BBcode)';
    var iframe = '<iframe src="' + wikiUrl + '" style="width: 100%; height: 100%; border: none;"></iframe>';

    $('#tetherIframe').html(iframe);

    console.log('Loading wiki:', wikiUrl);
});
</script>
```

## Why This Will Work:

1. **Inline jQuery** - Uses jQuery which XenForo already has
2. **Simple URLSearchParams** - Reads query parameters reliably
3. **Console logging** - You can see what's happening in browser console (F12)
4. **No complex logic** - Just straightforward parameter reading and display

## Testing Steps:

1. **Paste the HTML above** into your custom page
2. **Save** the page
3. **Test the direct URL** first:
   ```
   https://spherical-worlds.com/tsbeta/pages/tether/?name=Test&boon=5&bane=1
   ```
   You should see the counter and iframe.

4. **Then test clicking a tether** in a post
5. **Open browser console** (F12) and check for any errors or the console.log messages

## If It Still Shows "Loading...":

Check the browser console (F12) and tell me what errors you see!
