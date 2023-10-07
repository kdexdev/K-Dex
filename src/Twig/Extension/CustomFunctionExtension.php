<?php

namespace App\Twig\Extension;

use LogicException;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;
use enshrined\svgSanitize\Sanitizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CustomFunctionExtension extends AbstractExtension
{
    private static string $projectDir;
    private static string $projectAssetsDir;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        self::$projectDir = $parameterBag->get('kernel.project_dir');
        self::$projectAssetsDir = self::$projectDir . '/assets';
    }

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
        $completeFilePath = self::$projectAssetsDir . '/' . $svgFilePath;
        if (!file_exists($completeFilePath)) {
            throw new LogicException('SVG file not found: ' . $svgFilePath);
        }
        $svg = file_get_contents($completeFilePath);

        // Purify and minimize the SVG
        $sanitizer = new Sanitizer();
        $sanitizer->removeRemoteReferences(true);
        $sanitizer->minify(true);
        $svg = $sanitizer->sanitize($svg);

        // Remove the XML document tag
        $svg = preg_replace("/<\?xml[^>]*>/", "", $svg);

        // Place it inside a div.icon
        $svg = '<div class="icon">' . $svg . '</div>';

        return new Markup($svg, 'utf-8');
    }
}
