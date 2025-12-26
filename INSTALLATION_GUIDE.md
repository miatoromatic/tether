# Tether System Add-on for XenForo 2.2.8 - Installation Guide

## Overview
This add-on allows users to display Tether items in forum posts using custom BBCode. Each tether displays:
- An image from `/db/tethers/`
- Positive and negative values
- A clickable popup showing the tether with a color-coded border and embedded wiki page

## Installation Steps

### 1. Upload Files
Upload the entire `upload` directory to your XenForo installation root. The structure should be:
```
your-xenforo-root/
├── src/
│   └── addons/
│       └── TerrARP/
│           └── Tether/
│               ├── addon.json
│               ├── BbCode/
│               │   └── Tether.php
│               └── Setup.php
└── js/
    └── terrarp/
        └── tether/
            └── tether-popup.js
```

### 2. Create Tether Images Directory
Create the directory for tether images:
```
your-xenforo-root/db/tethers/
```

Place your `.webp` tether images in this directory with filenames matching the tether names (lowercase, underscores for spaces).

Example: `arachnas_swansong.webp`

### 3. Install the Add-on
1. Log in to your XenForo Admin Control Panel (Admin CP)
2. Navigate to: **Add-ons** → **Install add-on**
3. Click **Install from archive**
4. Upload the addon ZIP or use **Install add-on** and enter: `TerrARP/Tether`
5. Click **Install**

### 4. Configure the BBCode

#### Navigate to BB Codes
1. In Admin CP, go to: **Content** → **BB codes** → **Add BB code**

#### Fill in the BBCode Form

**BB code tag:** `tether`

**Title:** `Tether Display`

**Description:** `Displays a tether item with positive/negative values and wiki link`

**Replacement mode:** Select **PHP callback**

**Supports option parameter:** Select **Yes** (IMPORTANT!)

**PHP callback:**
```
TerrARP\Tether\BbCode\Tether::renderTag
```

**Example usage:**
```
[tether=arachnas_swansong]positive1,negative5[/tether]
```

**Example output:**
```
Displays the Arachnas Swansong tether image with Positive: 1 and Negative: 5 below it
```

**Advanced Options:**

- Leave "Option match regular expression" empty
- Check all checkboxes under "Within this BB code" section:
  - ☑ Disable smilies
  - ☑ Disable line break conversion
  - ☑ Disable auto-linking
  - ☑ Stop parsing BB code

- **Trim line breaks after:** `0`
- Leave **HTML email replacement** empty (or copy HTML replacement if you want)
- Leave **Text replacement** empty

**Allow this BB code in signatures:** Your choice (recommended: unchecked)

**Add-on:** Select `Tether System` from dropdown

Click **Save**

### 5. Include JavaScript (Choose One Option)

The popup functionality requires JavaScript. You have two options:

#### Option A: Automatic Inline (Default - No Template Modification)

**How it works:**
- JavaScript is automatically embedded in the page when the first tether BBCode is rendered
- Works immediately after BBCode configuration
- No template modifications needed

**Pros:**
- Zero configuration - works out of the box
- No template changes required
- Perfect for testing and quick setup

**Cons:**
- JavaScript is included in page HTML (slightly larger page size)
- Included on every page with tethers

**To use:** Nothing to do - this is the default behavior!

---

#### Option B: External JavaScript File (Recommended for Production)

**How it works:**
- Load JavaScript from a separate file in your page template
- Cleaner HTML output
- Better browser caching

**Pros:**
- Cleaner HTML (no inline scripts)
- Better browser caching
- Smaller page size on pages with multiple tethers
- More professional approach

**Cons:**
- Requires one-time template modification

**Setup Instructions:**

1. In Admin CP, go to: **Appearance** → **Templates**
2. Search for and click: **page_container**
3. Find the `</body>` closing tag (near the very end of the template)
4. Add this line just **before** `</body>`:
   ```html
   <script src="/js/terrarp/tether/tether-popup.js"></script>
   ```
5. Click **Save**

**Example - What it should look like:**
```html
    ... other template content ...

    <script src="/js/terrarp/tether/tether-popup.js"></script>
</body>
</html>
```

**Alternative: Create a Template Modification**

For a cleaner upgrade path, you can create a template modification instead:

1. Admin CP → **Appearance** → **Template modifications** → **Add template modification**
2. **Template:** `page_container`
3. **Modification key:** `terrarp_tether_js`
4. **Description:** `Include Tether popup JavaScript`
5. **Execution order:** `10`
6. **Action:** Find and replace
7. **Find:**
   ```html
   </body>
   ```
8. **Replace:**
   ```html
   <script src="/js/terrarp/tether/tether-popup.js"></script>
   </body>
   ```
9. Click **Save**

**Note:** Both options work together! If you use Option B, the inline JavaScript (Option A) will detect that the function already exists and won't duplicate it.

---

### 6. Test the BBCode

Create a test post with:
```
[tether=arachnas_swansong]positive1,negative5[/tether]
[tether=test_tether]positive3,negative1[/tether]
```

This should display:
- Two tether images side by side
- Each with Positive/Negative values below
- Clickable images that open a styled popup with embedded wiki

## Usage

### BBCode Syntax
```
[tether=tether_name]positive#,negative#[/tether]
```

**Parameters:**
- `tether_name`: The name of the tether (matches the image filename without .webp extension)
- `positive#`: Positive value (number)
- `negative#`: Negative value (number)

### Examples
```
[tether=arachnas_swansong]positive1,negative5[/tether]
[tether=shadow_cloak]positive2,negative2[/tether]
[tether=fire_essence]positive5,negative1[/tether]
```

### Color Coding
The border color automatically adjusts based on the difference between positive and negative values:

- **Bright Green (#00ff00):** Difference > 2 (very positive)
- **Green (#22aa22):** Difference > 0 (positive)
- **Gray (#888888):** Difference = 0 (neutral)
- **Orange (#ff6600):** Difference > -3 (negative)
- **Red (#ff0000):** Difference ≤ -3 (very negative)

## File Naming Conventions

### Image Files
- Location: `/db/tethers/`
- Format: `.webp`
- Naming: lowercase with underscores
  - Example: `arachnas_swansong.webp` for tether named "Arachnas Swansong"

### Wiki URLs
Automatically generated as:
```
https://terrarp.com/wiki/{tether_name}_(tether)
```
Example: `https://terrarp.com/wiki/arachnas_swansong_(tether)`

## Troubleshooting

### Images Not Displaying
- Verify the image file exists in `/db/tethers/`
- Check the filename matches the tether name (lowercase, underscores)
- Ensure the file has `.webp` extension
- Check file permissions (should be readable by web server)

### Popup Not Opening
- Verify the JavaScript file is included in the template
- Check browser console for JavaScript errors
- Ensure popup blockers are disabled
- Try opening in a different browser

### BBCode Not Rendering
- Verify the PHP callback is correct: `TerrARP\Tether\BbCode\Tether::renderTag`
- Check that "Supports option parameter" is set to **Yes**
- Ensure the add-on is installed and enabled
- Clear XenForo cache: **Tools** → **Rebuild caches**

### Wiki Page Not Loading in Popup
- This may be due to X-Frame-Options or Content-Security-Policy headers on the wiki
- If the iframe fails to load, users can still click "Open Full Wiki Page" button
- Consider adjusting wiki server headers to allow framing from your domain

## Customization

### Adjusting Border Width
Edit `/upload/src/addons/TerrARP/Tether/BbCode/Tether.php`, line with:
```php
border: 3px solid %s;
```
Change `3px` to your desired width.

### Adjusting Color Scheme
Edit the `getBorderColor()` method in `/upload/src/addons/TerrARP/Tether/BbCode/Tether.php`

### Adjusting Popup Size
Edit `/upload/js/terrarp/tether/tether-popup.js`, lines:
```javascript
var width = 900;
var height = 700;
```

### Changing Image Size
Edit the inline style in `/upload/src/addons/TerrARP/Tether/BbCode/Tether.php`, line:
```php
max-width: 150px;
```

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Verify all installation steps were completed
3. Check XenForo error logs: **Tools** → **Logs** → **Server error log**

## License
This add-on is provided as-is for use with XenForo 2.2.8.
