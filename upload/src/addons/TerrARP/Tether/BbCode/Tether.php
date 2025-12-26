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

        // Get the content (positive and negative values)
        $content = $renderer->renderSubTree($tagChildren, $options);
        $content = trim(strip_tags($content));

        // Parse positive and negative values
        $values = self::parseValues($content);

        if ($values === null)
        {
            return '<span class="bbCodeError">Invalid tether format. Use: positive#,negative#</span>';
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
        $borderColor = self::getBorderColor($values['positive'], $values['negative']);

        // Generate unique ID for this tether instance
        $uniqueId = 'tether-' . md5($tetherName . $content . microtime());

        // Include JavaScript once per page (before the first tether)
        $jsScript = '';
        if (!self::$jsIncluded)
        {
            $jsScript = self::getJavaScript();
            self::$jsIncluded = true;
        }

        // Build the HTML output
        $html = $jsScript . sprintf(
            '<div class="tether-container" style="display: inline-block; margin: 5px; text-align: center; vertical-align: top;">
                <div class="tether-image-wrapper" style="border: 3px solid %s; padding: 3px; border-radius: 4px; background: #fff;">
                    <img src="%s" alt="%s" class="tether-image" style="max-width: 150px; height: auto; display: block; cursor: pointer;" onclick="openTetherPopup(\'%s\', \'%s\', %d, %d, \'%s\')" />
                </div>
                <div class="tether-stats" style="font-size: 11px; margin-top: 5px; padding: 5px; background: #f5f5f5; border-radius: 3px;">
                    <strong style="color: #22aa22;">Positive:</strong> %d<br>
                    <strong style="color: #aa2222;">Negative:</strong> %d
                </div>
            </div>',
            htmlspecialchars($borderColor),
            htmlspecialchars($imageUrl),
            htmlspecialchars($tetherName),
            htmlspecialchars($wikiUrl),
            htmlspecialchars($imageUrl),
            (int)$values['positive'],
            (int)$values['negative'],
            htmlspecialchars($borderColor),
            (int)$values['positive'],
            (int)$values['negative']
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
    function openTetherPopup(wikiUrl, imageUrl, positive, negative, borderColor) {
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
            "/* Base popup styles */" +
            "body { margin: 0; padding: 0; overflow: hidden; }" +
            ".iframe-container { width: 100%; height: 100vh; }" +
            "iframe { width: 100%; height: 100%; border: none; display: block; }" +
            "" +
            "/* ============================================== */" +
            "/* CUSTOM CSS - Edit below to style wiki content */" +
            "/* ============================================== */" +
            "" +
            "/* Example: Add a border based on tether values */" +
            "/* You can inject CSS that affects the iframe content if same-origin */" +
            "" +
            "/* Uncomment and customize as needed: */" +
            "/*" +
            ".tether-positive-" + positive + " {" +
            "  border: 5px solid " + borderColor + " !important;" +
            "}" +
            "*/" +
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
     * Parse the positive and negative values from content
     *
     * @param string $content Content in format "positive#,negative#"
     * @return array|null Array with 'positive' and 'negative' keys, or null if invalid
     */
    private static function parseValues($content)
    {
        // Expected format: positive#,negative#
        // Examples: positive1,negative5 or positive2,negative3

        if (!preg_match('/^positive(\d+)\s*,\s*negative(\d+)$/i', $content, $matches))
        {
            return null;
        }

        return [
            'positive' => (int)$matches[1],
            'negative' => (int)$matches[2]
        ];
    }

    /**
     * Determine border color based on positive/negative values
     *
     * @param int $positive Positive value
     * @param int $negative Negative value
     * @return string Hex color code
     */
    private static function getBorderColor($positive, $negative)
    {
        $diff = $positive - $negative;

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
