# XenForo Overlay Setup Guide - Tether System

## Step 1: Create Custom Page in XenForo

1. **Navigate to Admin CP → Nodes**
2. **Click "Add node" → Page**
3. Fill in the following:

### Basic Information
- **URL portion:** `tether`
- **Title:** `Tether Information`
- **Description:** (leave empty)

### Page Setup
- **Template HTML:** (paste the code from Step 2 below)

### Advanced Options
- **Node name:** `Tether`
- **Display in the node list:** ☐ Unchecked

4. **Click "Save"**

---

## Step 2: Custom Page HTML Template

Copy and paste this entire code into the **Template HTML** field:

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
}

.counter {
    padding: 10px;
    margin: 10px 20px;
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
    margin: 0 20px 20px 20px;
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
    // Get URL parameters
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    // Get parameters from URL
    var tetherName = getUrlParameter('name');
    var boon = parseInt(getUrlParameter('boon')) || 0;
    var bane = parseInt(getUrlParameter('bane')) || 0;

    var counterElement = document.getElementById('tetherCounter');
    var iframeElement = document.getElementById('tetherIframe');

    if (!tetherName) {
        counterElement.innerHTML = '<span class="error-message">Error: No tether specified</span>';
        return;
    }

    // Build wiki URL
    var wikiUrl = 'https://terrarp.com/wiki/' + tetherName + '_(BBcode)';

    // Display counter with colored values
    counterElement.innerHTML =
        '<span class="boon">Boon:</span> <span class="boon">' + boon + '</span> · ' +
        '<span class="bane">Bane:</span> <span class="bane">' + bane + '</span>';

    // Load wiki in iframe
    iframeElement.src = wikiUrl;
})();
</script>
```

---

## Step 3: Update Your Tether.php

The addon now generates links like:
```
/pages/tether/?name=Gondoliers_Mercy&boon=5&bane=1
```

Instead of opening a popup window, it will use XenForo's overlay system!

---

## Step 4: Remove Old JavaScript (Optional)

Since we're no longer using popup windows, you can now **remove** this line from your `page_container` template:

```html
<script src="/js/terrarp/tether/tether-popup.js"></script>
```

**Or** just leave it - it won't cause any issues, it just won't be used anymore.

---

## Benefits of Using XenForo Overlay

✅ **No popup blockers** - Uses XenForo's built-in overlay system
✅ **Better UX** - Users can click outside to close
✅ **Consistent styling** - Matches XenForo's UI
✅ **Mobile friendly** - Works great on all devices
✅ **Easier navigation** - Forum stays in background
✅ **No external JavaScript needed** - All self-contained

---

## Testing

1. Upload the updated `Tether.php` file
2. Create the custom page in XenForo with the HTML above
3. Rebuild XenForo cache
4. Test with: `[tether=gondoliers mercy]boon5,bane1[/tether]`
5. Click the tether image - it should open in an overlay!

---

## Customization

### Change Overlay Height
Edit the `.iframe-container` height in the CSS:
```css
height: 70vh;  /* Change this value */
min-height: 500px;  /* And this */
```

### Change Counter Styling
Edit the `.counter` class:
```css
font-size: 1.4em;  /* Make larger/smaller */
text-align: left;  /* Or center/right */
```

### Change Background Color
Edit the background:
```css
background: #101219;  /* Your color here */
```

---

## URL Structure

The overlay URL format is:
```
/pages/tether/?name={TetherName}&boon={#}&bane={#}
```

Examples:
- `/pages/tether/?name=Gondoliers_Mercy&boon=5&bane=1`
- `/pages/tether/?name=Shadow_Cloak&boon=2&bane=2`
- `/pages/tether/?name=Fire_Essence&boon=5&bane=1`

The JavaScript reads these parameters and builds the wiki URL + counter display automatically!

---

## Troubleshooting

**Overlay doesn't open:**
- Make sure you created the page with URL portion: `tether`
- Check that the page is published (not in draft mode)
- Verify XenForo cache is rebuilt

**Wiki doesn't load:**
- Check browser console (F12) for errors
- Verify the wiki URL is correct in the generated HTML
- Check if wiki allows iframe embedding (X-Frame-Options)

**Counter shows wrong values:**
- Check the URL in browser address bar when overlay opens
- Verify parameters are being passed correctly
- Check JavaScript console for errors

---

## Notes

- The overlay is responsive and works on mobile devices
- You can style the overlay however you want with CSS
- The iframe will inherit XenForo's overlay close functionality
- Users can press ESC or click outside to close the overlay
