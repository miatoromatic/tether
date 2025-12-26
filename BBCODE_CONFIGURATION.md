# XenForo BBCode Configuration - Quick Reference

## Complete BBCode Setup Form Values

When creating the custom BB code in XenForo Admin CP (**Content** → **BB codes** → **Add BB code**), use these exact values:

---

### **BB code tag**
```
tether
```

### **Title**
```
Tether Display
```

### **Description**
```
Displays a tether item with positive/negative values and wiki link popup
```

### **Replacement mode**
- ⚫ Simple replacement
- **🔘 PHP callback** ← SELECT THIS

### **Supports option parameter**
- **🔘 Yes** ← SELECT THIS
- ⚫ No
- ⚫ Optional

### **HTML replacement**
```
Leave empty (not used when PHP callback is selected)
```

### **PHP callback**
```
TerrARP\Tether\BbCode\Tether::renderTag
```

⚠️ **IMPORTANT:** Make sure there are **two colons (::)** between the class name and method name!

### **Editor icon**
- **🔘 None** ← SELECT THIS
- ⚫ Font Awesome icon
- ⚫ Image

### **Example usage**
```
[tether=arachnas_swansong]positive1,negative5[/tether]
```

### **Example output**
```
Displays the Arachnas Swansong tether image with a colored border. Shows "Positive: 1" and "Negative: 5" below the image. Clicking opens a popup with wiki information.
```

### **Allow this BB code in signatures**
- ⬜ Enabled ← UNCHECK (recommended)

### **Add-on**
```
Select: Tether System
```

---

## Advanced Options (Expand this section)

### **Option match regular expression**
```
Leave empty
```

### **Within this BB code**
- ☑ **Disable smilies** ← CHECK THIS
- ☑ **Disable line break conversion** ← CHECK THIS
- ☑ **Disable auto-linking** ← CHECK THIS
- ☑ **Stop parsing BB code** ← CHECK THIS

### **Display HTML replacement when empty**
- ⬜ Unchecked ← LEAVE UNCHECKED

### **Trim line breaks after**
```
0
```

### **HTML email replacement**
```
Leave empty (will use default HTML replacement)
```

### **Text replacement**
```
Leave empty
```

---

## Verification Checklist

After saving the BBCode configuration, verify:

- [ ] BB code tag is exactly `tether` (lowercase, no spaces)
- [ ] "Replacement mode" is set to "PHP callback"
- [ ] "Supports option parameter" is set to "Yes"
- [ ] PHP callback is exactly: `TerrARP\Tether\BbCode\Tether::renderTag`
- [ ] All four checkboxes under "Within this BB code" are checked
- [ ] Add-on is selected as "Tether System"

## Testing the BBCode

After configuration, create a test post:

```
[tether=test]positive3,negative1[/tether]
```

**Expected Result:**
- Image displayed from `/db/tethers/test.webp`
- Text below showing "Positive: 3" and "Negative: 5"
- Green border (since positive > negative)
- Clicking image opens popup

**If you see raw BBCode instead:**
1. Check that the PHP callback is correct (with `::`)
2. Verify the add-on is installed and enabled
3. Clear XenForo cache: **Tools** → **Rebuild caches** → **Rebuild master data**
4. Check XenForo error log for PHP errors

**If you see "Invalid tether format" error:**
- The content format must be exactly: `positive#,negative#`
- Example: `positive1,negative5` (not `1,5` or `pos1,neg5`)

## Multiple Tethers in One Post

You can use multiple tether tags in a single post:

```
[tether=shadow_cloak]positive2,negative2[/tether]
[tether=fire_essence]positive5,negative1[/tether]
[tether=ice_shield]positive1,negative4[/tether]
```

This will display all three tethers in a row, each with their respective values and colored borders.

## Customization Notes

### Changing the Wiki Domain
If your wiki is not at `https://terrarp.com/wiki/`, edit this line in:
`/upload/src/addons/TerrARP/Tether/BbCode/Tether.php` (line ~48):

```php
$wikiUrl = 'https://terrarp.com/wiki/' . $tetherUrlName . '_(tether)';
```

Change to:
```php
$wikiUrl = 'https://your-domain.com/wiki/' . $tetherUrlName . '_(tether)';
```

### Changing Image Location
If your images are not in `/db/tethers/`, edit this line in the same file (line ~45):

```php
$imageUrl = '/db/tethers/' . $tetherUrlName . '.webp';
```

Change to your image path:
```php
$imageUrl = '/your/path/' . $tetherUrlName . '.webp';
```

After making changes, rebuild the XenForo cache!
