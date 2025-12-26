# Tether System - XenForo 2.2.8 Add-on

A lightweight XenForo add-on that enables users to display Tether items in forum posts using custom BBCode.

## Features

✅ **Custom BBCode** - Simple `[tether=name]positive#,negative#[/tether]` syntax
✅ **Visual Display** - Shows tether images with positive/negative values
✅ **Color-Coded Borders** - Automatic border colors based on value differences
✅ **Interactive Popups** - Click to view detailed tether info with embedded wiki
✅ **No Database Required** - File-based system for maximum reliability
✅ **Easy Installation** - Simple upload and configure process

## Quick Start

### 1. Install
```bash
# Upload files to your XenForo root directory
# Install via Admin CP → Add-ons → Install add-on
```

### 2. Configure BBCode
In Admin CP → Content → BB codes → Add BB code:
- **Tag:** `tether`
- **Replacement mode:** PHP callback
- **PHP callback:** `TerrARP\Tether\BbCode\Tether::renderTag`
- **Supports option:** Yes

See [BBCODE_CONFIGURATION.md](BBCODE_CONFIGURATION.md) for complete setup details.

### 3. Add JavaScript
Add to your `page_container` template before `</body>`:
```html
<script src="/js/terrarp/tether/tether-popup.js"></script>
```

### 4. Add Tether Images
Place `.webp` images in `/db/tethers/` directory with lowercase names:
```
/db/tethers/arachnas_swansong.webp
/db/tethers/shadow_cloak.webp
```

## Usage Example

```
[tether=arachnas_swansong]positive1,negative5[/tether]
[tether=shadow_cloak]positive2,negative2[/tether]
[tether=fire_essence]positive5,negative1[/tether]
```

**Result:** Displays three tether images with colored borders and stats. Clicking any image opens a popup showing the tether with an embedded wiki page.

## Border Color System

The add-on automatically applies colored borders based on the difference between positive and negative values:

| Difference | Color | Meaning |
|------------|-------|---------|
| > 2 | Bright Green | Very Positive |
| > 0 | Green | Positive |
| = 0 | Gray | Neutral |
| > -3 | Orange | Negative |
| ≤ -3 | Red | Very Negative |

## File Structure

```
upload/
├── src/addons/TerrARP/Tether/
│   ├── addon.json              # Add-on definition
│   ├── Setup.php               # Installation handler
│   └── BbCode/
│       └── Tether.php          # BBCode rendering logic
└── js/terrarp/tether/
    └── tether-popup.js         # Popup window handler
```

## Documentation

- **[INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)** - Complete installation instructions
- **[BBCODE_CONFIGURATION.md](BBCODE_CONFIGURATION.md)** - BBCode setup reference
- **[TECHNICAL_NOTES.md](TECHNICAL_NOTES.md)** - Implementation details and limitations

## About the Border Styling Solution

**Your Question:** "I would really like to put a border around the perk on the resulting wiki OR html file that gets pulled up in the popup based on the positive or negative value inside the bbcode, but I am not sure this is possible"

**The Solution:**

Due to browser security restrictions (Same-Origin Policy), we cannot directly modify content on the wiki page if it's hosted on a different domain. However, this add-on implements an elegant workaround:

1. **Styled Popup Container** - The popup window displays the tether image with a prominently colored border that changes based on positive/negative values
2. **Visual Hierarchy** - The bordered tether image appears at the top of the popup, making the color-coding immediately visible
3. **Embedded Wiki** - The wiki page loads in an iframe below the tether display
4. **Consistent Color Coding** - The same border color appears on both the forum post and the popup

This approach provides the visual feedback you want (color-coded borders based on values) while respecting browser security constraints.

### Example Flow:
1. User sees tether in post with green border (positive > negative)
2. User clicks image
3. Popup opens showing:
   - Large tether image with green border (5px thick)
   - Positive/Negative values displayed
   - Button to open full wiki page
   - Wiki page embedded below

**Alternative Considered:**
We could fetch the wiki HTML and modify it server-side, but this would:
- Violate the wiki's terms of service
- Create caching and performance issues
- Break if the wiki structure changes
- Potentially have copyright implications

The implemented solution provides the best user experience within technical and legal constraints.

## Requirements

- XenForo 2.2.8 Patch 1 or higher
- PHP 7.2.0 or higher
- Web server with `.webp` image support

## Browser Compatibility

- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support (Safari 14+)
- Popup blockers must allow popups from your domain

## Customization

All styling can be customized by editing:
- `/upload/src/addons/TerrARP/Tether/BbCode/Tether.php` - Forum post display
- `/upload/js/terrarp/tether/tether-popup.js` - Popup window styling

Color schemes, border widths, image sizes, and popup dimensions are all configurable.

## Troubleshooting

**BBCode shows as text:**
- Verify PHP callback is correct with `::`
- Check "Supports option parameter" is set to "Yes"
- Rebuild cache: Tools → Rebuild caches → Rebuild master data

**Images not loading:**
- Check file exists: `/db/tethers/tether_name.webp`
- Verify filename is lowercase with underscores
- Check file permissions (readable by web server)

**Popup not opening:**
- Verify JavaScript is included in template
- Check browser console for errors
- Disable popup blocker for your domain

See [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) for detailed troubleshooting.

## Version

**Current Version:** 1.0.0
**XenForo Version:** 2.2.8 Patch 1
**Release Date:** December 2024

## License

This add-on is provided as-is for use with XenForo 2.2.8.

## Support

For issues or questions, refer to the documentation files in this repository.