/**
 * Opens a popup window displaying the tether wiki page
 *
 * @param {string} wikiUrl - The URL to the wiki page
 * @param {string} imageUrl - The URL to the tether image (unused but kept for compatibility)
 * @param {number} boon - The boon value
 * @param {number} bane - The bane value
 * @param {string} borderColor - The border color based on values
 */
function openTetherPopup(wikiUrl, imageUrl, boon, bane, borderColor) {
    // Create popup window
    var width = 1000;
    var height = 800;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;

    var popup = window.open('', 'TetherPopup',
        'width=' + width +
        ',height=' + height +
        ',left=' + left +
        ',top=' + top +
        ',resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no'
    );

    if (!popup) {
        // Popup blocked, fall back to opening in new tab
        window.open(wikiUrl, '_blank');
        return;
    }

    // Build the HTML content for the popup
    var html = '<!DOCTYPE html>' +
        '<html>' +
        '<head>' +
        '<meta charset="UTF-8">' +
        '<title>Tether Wiki</title>' +
        '<style>' +
        'body { margin: 0; padding: 0; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,Liberation Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji; background: #090909; }' +
        '.counter { background-color: #1c202d; padding: 5px; margin: 5px 20px; color: #eee; font-size: 14px; font-weight: 600; }' +
        '.boon { color: #51bbb1; }' +
        '.bane { color: #d24b7e; }' +
        '.iframe-container { background: transparent; overflow: hidden; height: calc(100vh - 60px); }' +
        'iframe { width: 100%; height: 100%; border: none; display: block; }' +
        '' +
        '/* ============================================== */' +
        '/* CUSTOM CSS - Edit below to style wiki content */' +
        '/* ============================================== */' +
        '' +
        '/* Add your custom styles here */' +
        '' +
        '' +
        '/* ============================================== */' +
        '/* END CUSTOM CSS                                */' +
        '/* ============================================== */' +
        '</style>' +
        '</head>' +
        '<body>' +
        '<div class="counter">' +
        '<span class="boon">Boon:</span> ' + boon + ' | ' +
        '<span class="bane">Bane:</span> ' + bane +
        '</div>' +
        '<div class="iframe-container">' +
        '<iframe src="' + wikiUrl + '" title="Tether Wiki"></iframe>' +
        '</div>' +
        '</body>' +
        '</html>';

    // Write the HTML to the popup window
    popup.document.open();
    popup.document.write(html);
    popup.document.close();
}
