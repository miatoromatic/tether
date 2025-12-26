/**
 * Opens a popup window displaying tether information with a styled border
 * based on positive/negative values
 *
 * @param {string} wikiUrl - The URL to the wiki page
 * @param {string} imageUrl - The URL to the tether image
 * @param {number} positive - The positive value
 * @param {number} negative - The negative value
 * @param {string} borderColor - The border color based on values
 */
function openTetherPopup(wikiUrl, imageUrl, positive, negative, borderColor) {
    // Create popup window
    var width = 900;
    var height = 700;
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
        '<title>Tether Information</title>' +
        '<style>' +
        'body { margin: 0; padding: 20px; font-family: Arial, sans-serif; background: #f0f0f0; }' +
        '.tether-header { text-align: center; background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }' +
        '.tether-image-container { display: inline-block; border: 5px solid ' + borderColor + '; padding: 10px; border-radius: 8px; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }' +
        '.tether-image { max-width: 200px; height: auto; display: block; }' +
        '.tether-stats { margin-top: 15px; font-size: 16px; }' +
        '.tether-stats div { margin: 8px 0; }' +
        '.positive { color: #22aa22; font-weight: bold; }' +
        '.negative { color: #aa2222; font-weight: bold; }' +
        '.wiki-link { display: inline-block; margin-top: 20px; padding: 12px 24px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background 0.3s; }' +
        '.wiki-link:hover { background: #45a049; }' +
        '.iframe-container { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: calc(100vh - 320px); min-height: 400px; }' +
        'iframe { width: 100%; height: 100%; border: none; }' +
        '</style>' +
        '</head>' +
        '<body>' +
        '<div class="tether-header">' +
        '<div class="tether-image-container">' +
        '<img src="' + imageUrl + '" alt="Tether" class="tether-image" onerror="this.style.display=\'none\'; this.parentElement.innerHTML += \'<p>Image not found</p>\';" />' +
        '</div>' +
        '<div class="tether-stats">' +
        '<div><span class="positive">Positive:</span> ' + positive + '</div>' +
        '<div><span class="negative">Negative:</span> ' + negative + '</div>' +
        '</div>' +
        '<a href="' + wikiUrl + '" target="_blank" class="wiki-link">Open Full Wiki Page</a>' +
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
