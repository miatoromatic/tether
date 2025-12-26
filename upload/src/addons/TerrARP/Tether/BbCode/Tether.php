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

        // Get the content (positive and negative values)
        $content = $renderer->renderSubTree($tagChildren, $options);
        $content = trim(strip_tags($content));

        // Parse positive and negative values
        $values = self::parseValues($content);

        if ($values === null)
        {
            return '<span class="bbCodeError">Invalid tether format. Use: positive#,negative#</span>';
        }

        // Convert tether name for URL (replace spaces with underscores, lowercase)
        $tetherUrlName = str_replace(' ', '_', strtolower($tetherName));

        // Generate the image URL
        $imageUrl = '/db/tethers/' . $tetherUrlName . '.webp';

        // Generate the wiki URL
        $wikiUrl = 'https://terrarp.com/wiki/' . $tetherUrlName . '_(tether)';

        // Determine border color based on values
        $borderColor = self::getBorderColor($values['positive'], $values['negative']);

        // Generate unique ID for this tether instance
        $uniqueId = 'tether-' . md5($tetherName . $content . microtime());

        // Build the HTML output
        $html = sprintf(
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
}
