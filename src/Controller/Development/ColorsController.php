<?php

namespace App\Controller\Development;

use PhpParser\JsonDecoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ColorsController extends AbstractController
{
    #[Route('/dev/colors', name: 'dev_app_colors')]
    public function index(): Response
    {
        // Read the tailwind.config.js file
        $tailwindConfig = file_get_contents(
            $this->getParameter('kernel.project_dir') . '/tailwind.config.js'
        );


        preg_match('/' . //get out only the color array
            '(colors:\s*\{)(([\s\w' . "'" . ':]*)\{[^\}]*\},?)*(\s*\})' .
            '/s', $tailwindConfig, $matches);
        $jsonConfig = '{' . $matches[0] . '}';

        // 'Correct' the tailwind.config.js to JSON
        $jsonConfig = preg_replace('/' .
            '^\/\*[\s\S]*?\*\/' . '|' . //remove multiline comments
            '\s\/\/.*$' . '|' . //remove inline comments
            ',\s*(?=[\}\]])' . // remove trailing commas
            '/m', '', $jsonConfig);
        $jsonConfig = str_replace("'", "\"", $jsonConfig); //fix single quotes
        $jsonConfig = preg_replace('/([\d\w]+)\s*:/', '"$1":', $jsonConfig); //fix keys


        // Finally parse the tailwind.config.js file
        $parsedConfig = (new JsonDecoder)->decode($jsonConfig);

        // Get the colors from the config
        $colors = $parsedConfig['colors'];

        // Render the Twig template and pass the colors to it
        return $this->render('dev/colors/index.html.twig', [
            'controller_name' => 'ColorsController',
            'colors' => $colors,
        ]);
    }
}
