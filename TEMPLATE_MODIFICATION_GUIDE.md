# Template Modification Guide - External JavaScript

This guide shows you how to load the Tether popup JavaScript from an external file instead of using the automatic inline version.

## Why Use External JavaScript?

**Advantages:**
- ✅ Cleaner HTML output
- ✅ Better browser caching
- ✅ Smaller page size on pages with multiple tethers
- ✅ More professional approach
- ✅ Easier to update JavaScript without rebuilding caches

**When to Use:**
- Production sites
- Sites with many tether posts
- When you want optimal performance

## Method 1: Direct Template Edit (Quickest)

### Step-by-Step Instructions

1. **Log in to Admin CP**
   - Navigate to your XenForo Admin Control Panel

2. **Open Templates**
   - Click **Appearance** in the top navigation
   - Click **Templates** from the dropdown

3. **Search for page_container**
   - In the template search box, type: `page_container`
   - Click on **page_container** template to edit it

4. **Find the closing body tag**
   - Scroll to the very bottom of the template
   - Look for `</body>` (usually near the last few lines)

5. **Add the script tag**
   - Place your cursor on a new line just **before** `</body>`
   - Add this exact line:
   ```html
   <script src="/js/terrarp/tether/tether-popup.js"></script>
   ```

6. **Verify it looks correct**
   Your template should end like this:
   ```html
       ... other template content ...

       <script src="/js/terrarp/tether/tether-popup.js"></script>
   </body>
   </html>
   ```

7. **Save the template**
   - Click the **Save** button at the bottom

8. **Test**
   - Visit a page with tether BBCode
   - Click a tether image to verify the popup works

## Method 2: Template Modification (Better for Upgrades)

Template modifications are preserved during XenForo upgrades, making them ideal for production sites.

### Step-by-Step Instructions

1. **Log in to Admin CP**

2. **Navigate to Template Modifications**
   - Click **Appearance** in the top navigation
   - Click **Template modifications**

3. **Add Template Modification**
   - Click the **Add template modification** button

4. **Fill in the form:**

   **Add-on:** Select `Tether System` from dropdown

   **Template:** `page_container`

   **Modification key:** `terrarp_tether_js`

   **Description:** `Include Tether popup JavaScript`

   **Execution order:** `10`

   **Enabled:** ☑ Checked

   **Action:** Select `str_replace` (String: Find and replace)

   **Find:**
   ```html
   </body>
   ```

   **Replace:**
   ```html
   <script src="/js/terrarp/tether/tether-popup.js"></script>
   </body>
   ```

5. **Save**
   - Click **Save** at the bottom

6. **Verify**
   - Go to **Appearance** → **Template modifications**
   - You should see "Include Tether popup JavaScript" listed
   - Status should show as enabled

7. **Test**
   - Visit a page with tether BBCode
   - Click a tether image to verify the popup works

## Verifying the Setup

### Check if External JS is Loading

1. **View Page Source**
   - Visit any forum page
   - Right-click → View Page Source (or Ctrl+U / Cmd+U)
   - Search for: `tether-popup.js`
   - You should find: `<script src="/js/terrarp/tether/tether-popup.js"></script>`

2. **Check Browser Developer Tools**
   - Press F12 to open Developer Tools
   - Click the **Network** tab
   - Refresh the page
   - Look for `tether-popup.js` in the list of loaded files
   - Status should be **200** (successful)

3. **Test Popup Functionality**
   - Find or create a post with tether BBCode
   - Click on a tether image
   - Popup should open showing the tether with colored border and wiki

### Verify Inline JavaScript is Not Duplicating

After adding the external file:

1. View page source of a page with tethers
2. Search for `function openTetherPopup`
3. You should only see it **once** (in the external file)
4. You should **not** see `<script>if (typeof openTetherPopup` inline in the page

The inline version automatically detects the external function and won't duplicate it!

## Troubleshooting

### Popup Still Not Working

**Check file exists:**
```bash
# Verify the file is in the correct location
ls -la /path/to/xenforo/js/terrarp/tether/tether-popup.js
```

**Check file permissions:**
```bash
# File should be readable by web server
chmod 644 /path/to/xenforo/js/terrarp/tether/tether-popup.js
```

**Check browser console:**
1. Press F12
2. Click **Console** tab
3. Look for any errors related to `tether-popup.js`
4. Common errors:
   - `404 Not Found` - File not uploaded to correct location
   - `Failed to load resource` - Check file path and permissions

### Template Changes Not Showing

1. **Rebuild template cache:**
   - Admin CP → **Tools** → **Rebuild caches**
   - Select **Rebuild master data**
   - Click **Rebuild**

2. **Clear browser cache:**
   - Hard refresh: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)

3. **Check template modification is enabled:**
   - Admin CP → **Appearance** → **Template modifications**
   - Verify your modification shows as enabled

### Want to Revert to Inline JavaScript?

Simply remove the template modification or edit:

**Option 1: Delete Template Modification**
1. Admin CP → **Appearance** → **Template modifications**
2. Find "Include Tether popup JavaScript"
3. Click **Delete**

**Option 2: Edit Template Directly**
1. Admin CP → **Appearance** → **Templates** → `page_container`
2. Find and remove the line:
   ```html
   <script src="/js/terrarp/tether/tether-popup.js"></script>
   ```
3. Save

The inline JavaScript will automatically take over on the next page load!

## Performance Comparison

### With Inline JavaScript (Default)
- First tether on page: ~2KB JavaScript inline
- Additional tethers: No extra JavaScript
- Total page size: +2KB per page with tethers

### With External JavaScript (This Method)
- First page load: +2KB JavaScript file (cached by browser)
- Subsequent pages: 0KB (loaded from cache)
- Total page size: -2KB per page with tethers after first load

**Winner:** External JavaScript for sites with multiple pages containing tethers!

## Summary

| Method | Pros | Cons | Best For |
|--------|------|------|----------|
| **Inline (Default)** | No setup required | Larger page size | Testing, quick setup |
| **External (This Guide)** | Better caching, cleaner HTML | One-time template edit | Production sites |

Choose the method that best fits your needs. Both work perfectly!

## Questions?

- External file not loading? Check the file path is exactly `/js/terrarp/tether/tether-popup.js`
- Popup not opening? Check browser console (F12) for JavaScript errors
- Want to customize? Edit `tether-popup.js` and modify popup size, colors, or layout

For more help, see:
- [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) - Complete installation guide
- [TECHNICAL_NOTES.md](TECHNICAL_NOTES.md) - Technical details and customization
