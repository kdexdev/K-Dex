<?php

namespace App\Twig\Extension;

use LogicException;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;
use enshrined\svgSanitize\Sanitizer;

class CustomFunctionExtension extends AbstractExtension
{

    /******************************************
     ***                                    ***
     ***           TWIG FUNCTIONS           ***
     ***                                    ***
     ******************************************/

    /**
     * Returns an array of the created and registered custom Twig functions
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'svg_embed',
                [$this, 'embedSvgElement', 'is_safe' => ['html']]
            )
        ];
    }

    /**
     * Returns an embeddable SVG element
     *
     * @param string $svgFilePath The asset dir relative path of the SVG file
     * @return string Cleaned & minimized SVG
     *
     * @throws LogicException In case the passed SVG file doesn't exist
     */
    public function embedSvgElement(string $svgFilePath): Markup
    {
        // Read the SVG content from the file
        if (!file_exists($svgFilePath)) {
            throw new LogicException('SVG file not found: ' . $svgFilePath);
        }
        $svg = file_get_contents($svgFilePath);

        // Purify and minimize the SVG
        $sanitizer = new Sanitizer();
        $sanitizer->removeRemoteReferences(true);
        $sanitizer->minify(true);
        $svg = $sanitizer->sanitize($svg);

        // Remove the XML document tag
        $svg = preg_replace("/<\?xml[^>]*>/", "", $svg);

        return new Markup($svg, 'utf-8');
    }
}
