/**
 * Opens a popup window displaying the tether wiki page
 *
 * @param {string} wikiUrl - The URL to the wiki page
 * @param {string} imageUrl - The URL to the tether image (unused but kept for compatibility)
 * @param {number} positive - The positive value
 * @param {number} negative - The negative value
 * @param {string} borderColor - The border color based on values
 */
function openTetherPopup(wikiUrl, imageUrl, positive, negative, borderColor) {
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
        '/* Base popup styles */' +
        'body { margin: 0; padding: 0; overflow: hidden; }' +
        '.iframe-container { width: 100%; height: 100vh; }' +
        'iframe { width: 100%; height: 100%; border: none; display: block; }' +
        '' +
        '/* ============================================== */' +
        '/* CUSTOM CSS - Edit below to style wiki content */' +
        '/* ============================================== */' +
        '' +
        '/* Example: Add a border based on tether values */' +
        '/* You can inject CSS that affects the iframe content if same-origin */' +
        '' +
        '/* Uncomment and customize as needed: */' +
        '/*' +
        '.tether-positive-' + positive + ' {' +
        '  border: 5px solid ' + borderColor + ' !important;' +
        '}' +
        '*/' +
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
