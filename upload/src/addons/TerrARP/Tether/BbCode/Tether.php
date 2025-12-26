<?php

namespace TerrARP\Tether\BbCode;

class Tether
{
    /**
     * Track if we've already included the JavaScript on this page
     * @var bool
     */
    private static $jsIncluded = false;

    /**
     * Renders the tether BBCode
     *
     * @param array $tagChildren The content inside the BBCode tag
     * @param string $tagOption The value in the tag option (tether name)
     * @param array $tag The tag details
     * @param array $options Rendering options
     * @param \XF\BbCode\Renderer\AbstractRenderer $renderer The renderer instance
     * @return string The rendered HTML
     */
    public static function renderTag($tagChildren, $tagOption, $tag, array $options, \XF\BbCode\Renderer\AbstractRenderer $renderer)
    {
        // Get the tether name from the option
        $tetherName = trim($tagOption);

        if (empty($tetherName))
        {
            return '';
        }

        // Get the content (boon and bane values)
        $content = $renderer->renderSubTree($tagChildren, $options);
        $content = trim(strip_tags($content));

        // Parse boon and bane values
        $values = self::parseValues($content);

        if ($values === null)
        {
            return '<span class="bbCodeError">Invalid tether format. Use: boon#,bane#</span>';
        }

        // Convert tether name for image URL (lowercase with underscores)
        $imageUrlName = str_replace(' ', '_', strtolower($tetherName));

        // Convert tether name for wiki URL (Title_Case with underscores)
        $wikiUrlName = self::toTitleCase($tetherName);

        // Generate the image URL
        $imageUrl = '/db/tethers/' . $imageUrlName . '.webp';

        // Generate the wiki URL (every word capitalized)
        $wikiUrl = 'https://terrarp.com/wiki/' . $wikiUrlName . '_(Tether)';

        // Determine border color based on values
        $borderColor = self::getBorderColor($values['boon'], $values['bane']);

        // Generate unique ID for this tether instance
        $uniqueId = 'tether-' . md5($tetherName . $content . microtime());

        // Include JavaScript once per page (before the first tether)
        $jsScript = '';
        if (!self::$jsIncluded)
        {
            $jsScript = self::getJavaScript();
            self::$jsIncluded = true;
        }

        // Build the HTML output (no inline styles - user will define in forum CSS)
        $html = $jsScript . sprintf(
            '<div class="tether-container">
                <div class="tether-image-wrapper">
                    <img src="%s" alt="%s" class="tether-image" onclick="openTetherPopup(\'%s\', \'%s\', %d, %d, \'%s\')" />
                </div>
                <div class="tether-stats">
                    <span class="boon">Boon:</span> %d<br>
                    <span class="bane">Bane:</span> %d
                </div>
            </div>',
            htmlspecialchars($imageUrl),
            htmlspecialchars($tetherName),
            htmlspecialchars($wikiUrl),
            htmlspecialchars($imageUrl),
            (int)$values['boon'],
            (int)$values['bane'],
            htmlspecialchars($borderColor),
            (int)$values['boon'],
            (int)$values['bane']
        );

        return $html;
    }

    /**
     * Get the JavaScript code for popup functionality
     * Only included once per page
     *
     * @return string JavaScript code wrapped in script tags
     */
    private static function getJavaScript()
    {
        return '<script>
if (typeof openTetherPopup === "undefined") {
    function openTetherPopup(wikiUrl, imageUrl, boon, bane, borderColor) {
        var width = 1000;
        var height = 800;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;

        var popup = window.open("", "TetherPopup",
            "width=" + width +
            ",height=" + height +
            ",left=" + left +
            ",top=" + top +
            ",resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no"
        );

        if (!popup) {
            window.open(wikiUrl, "_blank");
            return;
        }

        var html = "<!DOCTYPE html>" +
            "<html>" +
            "<head>" +
            "<meta charset=\"UTF-8\">" +
            "<title>Tether Wiki</title>" +
            "<style>" +
            "body { margin: 0; padding: 0; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,Liberation Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji; background: #090909; }" +
            ".counter { background-color: #1c202d; padding: 5px; margin: 5px 20px; color: #eee; font-size: 14px; font-weight: 600; }" +
            ".boon { color: #51bbb1; }" +
            ".bane { color: #d24b7e; }" +
            ".iframe-container { background: transparent; overflow: hidden; height: calc(100vh - 60px); }" +
            "iframe { width: 100%; height: 100%; border: none; display: block; }" +
            "" +
            "/* ============================================== */" +
            "/* CUSTOM CSS - Edit below to style wiki content */" +
            "/* ============================================== */" +
            "" +
            "/* Add your custom styles here */" +
            "" +
            "" +
            "/* ============================================== */" +
            "/* END CUSTOM CSS                                */" +
            "/* ============================================== */" +
            "</style>" +
            "</head>" +
            "<body>" +
            "<div class=\"counter\">" +
            "<span class=\"boon\">Boon:</span> " + boon + " | " +
            "<span class=\"bane\">Bane:</span> " + bane +
            "</div>" +
            "<div class=\"iframe-container\">" +
            "<iframe src=\"" + wikiUrl + "\" title=\"Tether Wiki\"></iframe>" +
            "</div>" +
            "</body>" +
            "</html>";

        popup.document.open();
        popup.document.write(html);
        popup.document.close();
    }
}
</script>';
    }

    /**
     * Parse the boon and bane values from content
     *
     * @param string $content Content in format "boon#,bane#"
     * @return array|null Array with 'boon' and 'bane' keys, or null if invalid
     */
    private static function parseValues($content)
    {
        // Expected format: boon#,bane#
        // Examples: boon1,bane5 or boon2,bane3

        if (!preg_match('/^boon(\d+)\s*,\s*bane(\d+)$/i', $content, $matches))
        {
            return null;
        }

        return [
            'boon' => (int)$matches[1],
            'bane' => (int)$matches[2]
        ];
    }

    /**
     * Determine border color based on boon/bane values
     *
     * @param int $boon Boon value
     * @param int $bane Bane value
     * @return string Hex color code
     */
    private static function getBorderColor($boon, $bane)
    {
        $diff = $boon - $bane;

        if ($diff > 2)
        {
            // Very positive - bright green
            return '#00ff00';
        }
        elseif ($diff > 0)
        {
            // Positive - green
            return '#22aa22';
        }
        elseif ($diff === 0)
        {
            // Neutral - gray
            return '#888888';
        }
        elseif ($diff > -3)
        {
            // Negative - orange/red
            return '#ff6600';
        }
        else
        {
            // Very negative - red
            return '#ff0000';
        }
    }

    /**
     * Convert tether name to Title_Case for wiki URLs
     * Example: "gondoliers mercy" -> "Gondoliers_Mercy"
     *
     * @param string $name The tether name
     * @return string Title case with underscores
     */
    private static function toTitleCase($name)
    {
        // Replace spaces with underscores and capitalize each word
        $name = str_replace(' ', '_', $name);

        // Split by underscore, capitalize each word, rejoin
        $words = explode('_', $name);
        $words = array_map('ucfirst', array_map('strtolower', $words));

        return implode('_', $words);
    }
}
