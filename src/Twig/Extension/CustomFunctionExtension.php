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
            ),
            new TwigFunction(
                'mergeRecursively',
                [$this, 'recursiveMerge']
            )
        ];
    }

    /**
     * Returns an embeddable SVG element for the HTML markup.
     *
     * @param string $svgFilePath The asset dir relative path of the SVG file
     * @param array $classes (optional) Additional classes to be applied to the SVG element's container
     * @return Markup The HTML element containing the cleaned & minimized SVG
     *
     * @throws LogicException If the passed SVG file doesn't exist
     */
    public function embedSvgElement(string $svgFilePath, array $classes = []): Markup
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
        $classesString = "icon";
        foreach ($classes as $value) {
            $classesString .= " $value";
        }
        $svg = "<div class=\"$classesString\">$svg</div>";

        return new Markup($svg, 'utf-8');
    }

    /**
     * Recursively merges two arrays.
     *
     * @param array $array1 The first array to merge.
     * @param array $array2 The second array to merge.
     * @return array The merged array.
     */
    public function recursiveMerge(array $array1, array $array2): array
    {
        return array_merge_recursive($array1, $array2);
    }
}
