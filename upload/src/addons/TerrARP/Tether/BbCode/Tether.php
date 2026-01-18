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

        // Generate the overlay page URL
        $basePath = \XF::app()->request()->getBasePath();
        $overlayUrl = $basePath . '/pages/tether/';

        // Determine border color based on values (still used for popup)
        $borderColor = self::getBorderColor($values['boon'], $values['bane']);

        // Determine text colors for forum display
        $textColors = self::getTextColors($values['boon'], $values['bane']);

        // Build the HTML output with data attributes for overlay
        $html = sprintf(
            '<div class="tether-container">
                <div class="tether-image-wrapper">
                    <a href="%s"
                       data-xf-click="overlay"
                       data-tether-name="%s"
                       data-tether-boon="%d"
                       data-tether-bane="%d">
                        <img src="%s" alt="%s" class="tether-image" />
                    </a>
                </div>
                <div class="tether-stats">
                    <span class="boon" style="color: %s;">Boon: %d</span> ·
                    <span class="bane" style="color: %s;">Bane: %d</span>
                </div>
            </div>',
            htmlspecialchars($overlayUrl),
            htmlspecialchars($wikiUrlName),
            (int)$values['boon'],
            (int)$values['bane'],
            htmlspecialchars($imageUrl),
            htmlspecialchars($tetherName),
            htmlspecialchars($textColors['boon']),
            (int)$values['boon'],
            htmlspecialchars($textColors['bane']),
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
     * Determine text colors for boon and bane display
     *
     * @param int $boon Boon value
     * @param int $bane Bane value
     * @return array Array with 'boon' and 'bane' color keys
     */
    private static function getTextColors($boon, $bane)
    {
        $defaultColor = '#596a80';
        $boonColor = '#51bbb1';
        $baneColor = '#d24b7e';

        if ($boon > $bane)
        {
            // Boon is higher - highlight boon, gray out bane
            return [
                'boon' => $boonColor,
                'bane' => $defaultColor
            ];
        }
        elseif ($bane > $boon)
        {
            // Bane is higher - highlight bane, gray out boon
            return [
                'boon' => $defaultColor,
                'bane' => $baneColor
            ];
        }
        else
        {
            // Tied - both show their colors
            return [
                'boon' => $boonColor,
                'bane' => $baneColor
            ];
        }
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

        // Future expansion: Large differences get extreme colors
        if ($diff >= 6)
        {
            // Very high boon - bright green
            return '#00ff00';
        }
        elseif ($diff <= -6)
        {
            // Very high bane - red
            return '#ff0000';
        }
        // Standard logic
        elseif ($boon > $bane)
        {
            // Boon is higher - cyan (boon color)
            return '#51bbb1';
        }
        elseif ($bane > $boon)
        {
            // Bane is higher - pink (bane color)
            return '#d24b7e';
        }
        else
        {
            // Equal - dark gray
            return '#2e354a';
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
