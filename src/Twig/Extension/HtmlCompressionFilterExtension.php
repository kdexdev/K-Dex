<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use voku\helper\HtmlMin as VokuMinify;
use WyriHaximus\HtmlCompress\Factory as WyriFactory;
use WyriHaximus\HtmlCompress\HtmlCompressor as WyriMinify;

/**
 * A manual rewrite of nochso/html-compress-twig
 * Because I want to configure just how the HTML is compressed, dammit!
 */
class HtmlCompressionFilterExtension extends AbstractExtension
{
    private VokuMinify $vokuMinifier;
    private WyriMinify $wyriMinifier;

    public function __construct()
    {
        $this->vokuMinifier = new VokuMinify();
        $this->applySettingsToHtmlMinifier();
        $this->wyriMinifier =
            WyriFactory::constructSmallest()->withHtmlMin($this->vokuMinifier);
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'minifyHtml',
                [$this, 'minifyHtml'],
                [
                    'is_safe' => ['html']
                ]
            ),
        ];
    }

    /**
     * Minify the HTML string.
     *
     * @param string $html The HTML string to be minified.
     * @return string The minified HTML string.
     */
    public function minifyHtml(string $html): string
    {
        return $this->wyriMinifier->compress($html);
    }

    /**
     * Applying my custom settings to the minifier
     */
    private function applySettingsToHtmlMinifier(): void
    {
        $this->vokuMinifier->doOptimizeViaHtmlDomParser();

        // removes HTML comments
        $this->vokuMinifier->doRemoveComments();
        // Compresses multiple whitespace chars into a single space
        $this->vokuMinifier->doSumUpWhitespace();
        // removes whitespace around tags (</div>      <div> => </div><div>)
        $this->vokuMinifier->doRemoveWhitespaceAroundTags();

        // Various settings, aspects of which are enabled by the following configs:
        $this->vokuMinifier->doOptimizeAttributes();
        // removes empty attributes (class="" gets removed?)
        $this->vokuMinifier->doRemoveEmptyAttributes();
        // sort css-class-names, for better gzip results
        $this->vokuMinifier->doSortCssClassNames();
        // sort html-attributes, for better gzip results
        $this->vokuMinifier->doSortHtmlAttributes();
        // remove (more aggressively) spaces in the dom
        // (</a>      </div> => </a></div>)
        $this->vokuMinifier->doRemoveSpacesBetweenTags();

        // Comment the following back in when there's a static domain configured
        //$this->minifier->doMakeSameDomainsLinksRelative(['example.com']);
    }
}
