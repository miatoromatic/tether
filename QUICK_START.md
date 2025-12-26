# Quick Start Guide - Tether System

## New BBCode Format

### Usage
```
[tether=tether_name]boon#,bane#[/tether]
```

### Examples
```
[tether=gondoliers mercy]boon3,bane1[/tether]
[tether=shadow cloak]boon2,bane2[/tether]
[tether=fire essence]boon5,bane1[/tether]
```

## Installation Steps

### 1. Upload Files
Upload the `upload` directory contents to your XenForo root:
- `/src/addons/TerrARP/Tether/` - Addon files
- `/js/terrarp/tether/tether-popup.js` - Popup JavaScript
- `/db/tethers/` - Create this directory for tether images

### 2. Install Add-on
- Admin CP → **Add-ons** → **Install add-on**
- Enter: `TerrARP/Tether`
- Click **Install**

### 3. Configure BBCode
Admin CP → **Content** → **BB codes** → **Add BB code**

**Required Settings:**
- **BB code tag:** `tether`
- **Replacement mode:** PHP callback
- **PHP callback:** `TerrARP\Tether\BbCode\Tether::renderTag`
- **Supports option parameter:** Yes
- **Add-on:** Tether System

**Example usage:**
```
[tether=gondoliers mercy]boon3,bane1[/tether]
```

### 4. Add Forum CSS
Open your XenForo `extra.less` template and add the contents of `FORUM_CSS.css`:

Admin CP → **Appearance** → **Templates** → Search for **extra.less**

Paste this CSS:
```css
/* Tether Container */
.tether-container {
    display: inline-block;
    margin: 5px;
    text-align: center;
    vertical-align: top;
}

/* Tether Image Wrapper */
.tether-image-wrapper {
    border: 3px solid; /* Color set dynamically */
    padding: 3px;
    border-radius: 4px;
    background: #fff;
}

/* Tether Image */
.tether-image {
    max-width: 150px;
    height: auto;
    display: block;
    cursor: pointer;
}

/* Tether Stats */
.tether-stats {
    font-size: 11px;
    margin-top: 5px;
    padding: 5px;
    background: #f5f5f5;
    border-radius: 3px;
}

/* Boon/Bane Colors */
.boon { color: #51bbb1; font-weight: bold; }
.bane { color: #d24b7e; font-weight: bold; }
```

### 5. Add Tether Images
Place `.webp` images in `/db/tethers/` directory:
- Filename format: **lowercase with underscores**
- Example: `gondoliers_mercy.webp`

## Testing

Create a test post:
```
[tether=test]boon3,bane1[/tether]
```

**Expected Result:**
- Image shows from `/db/tethers/test.webp`
- "Boon: 3" in cyan (#51bbb1)
- "Bane: 1" in pink (#d24b7e)
- Green border (boon > bane)
- Clicking opens popup with wiki page

## Color System

Border colors are automatically set based on boon - bane difference:

| Difference | Color | Hex |
|------------|-------|-----|
| > 2 | Bright Green | #00ff00 |
| > 0 | Green | #22aa22 |
| = 0 | Gray | #888888 |
| > -3 | Orange | #ff6600 |
| ≤ -3 | Red | #ff0000 |

## Wiki URL Format

Wiki URLs are automatically generated with Title_Case:
- BBCode: `[tether=gondoliers mercy]boon3,bane1[/tether]`
- Wiki URL: `https://terrarp.com/wiki/Gondoliers_Mercy_(Tether)`

## Popup Window

Clicking a tether opens a 1000x800 popup window with:
- Dark theme background (#090909)
- Wiki page in iframe
- Custom CSS section for further customization

## Optional: External JavaScript

For better performance, you can load JavaScript externally:

**Option 1: Template Edit**
- Admin CP → **Appearance** → **Templates** → **page_container**
- Add before `</body>`:
```html
<script src="/js/terrarp/tether/tether-popup.js"></script>
```

**Option 2: Template Modification**
- Admin CP → **Appearance** → **Template modifications** → **Add template modification**
- Template: `page_container`
- Find: `</body>`
- Replace:
```html
<script src="/js/terrarp/tether/tether-popup.js"></script>
</body>
```

## Troubleshooting

**BBCode shows as text:**
- Check PHP callback has `::` (two colons)
- Verify "Supports option parameter" is set to "Yes"
- Rebuild cache: **Tools** → **Rebuild caches**

**Wrong format error:**
- Format must be: `boon#,bane#` (not positive/negative)
- Example: `boon3,bane1`

**Images not loading:**
- Check file exists: `/db/tethers/tether_name.webp`
- Filename must be lowercase with underscores

**Popup not working:**
- Check JavaScript is loaded (view page source)
- Check browser console for errors (F12)

## Need Help?

See full documentation:
- **INSTALLATION_GUIDE.md** - Detailed installation steps
- **BBCODE_CONFIGURATION.md** - Complete BBCode setup
- **FORUM_CSS.css** - Full CSS reference
- **TECHNICAL_NOTES.md** - Technical details

## Version Info

- **Format:** boon/bane (changed from positive/negative)
- **Boon Color:** #51bbb1 (cyan)
- **Bane Color:** #d24b7e (pink)
- **Popup Theme:** Dark (#090909 background)
