<?php

namespace TerrARP\Tether\BbCode;

class Tether
{
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
        $wikiUrl = 'https://terrarp.com/wiki/' . $wikiUrlName . '_(BBcode)';

        // Determine border color based on values
        $borderColor = self::getBorderColor($values['boon'], $values['bane']);

        // Build the HTML output with dynamic border color
        $html = sprintf(
            '<div class="tether-container" style="border-color: %s;">
                <div class="tether-image-wrapper">
                    <img src="%s" alt="%s" class="tether-image" onclick="openTetherPopup(\'%s\', \'%s\', %d, %d, \'%s\')" />
                </div>
                <div class="tether-stats">
                    <span class="boon">Boon:</span> %d ·
                    <span class="bane">Bane:</span> %d
                </div>
            </div>',
            htmlspecialchars($borderColor),
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
