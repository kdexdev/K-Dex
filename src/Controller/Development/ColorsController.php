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

        // 'Correct' the tailwind.config.js to JSON
        $jsonConfig = preg_replace('/' .
            '^\/\*[\s\S]*?\*\/' . '|' . //remove multiline comments
            '\s\/\/.*$' . '|' . //remove inline comments
            ',\s*(?=[\}\]])' . // remove trailing commas
            '/m', '', $tailwindConfig);
        $jsonConfig = str_replace("'", "\"", $jsonConfig); //fix single quotes
        $jsonConfig = preg_replace('/([\d\w]+)\s*:/', '"$1":', $jsonConfig); //fix keys
        preg_match('/' . //get out only the color array
            '("colors":\s*\{)' . //match the start of the array
            '((\s*("\w*":)\s*)(' . //match array keys
                '(\{[^\}]*\})' . //match inner arrays
                '|'.
                '("[#\w]*")' . //match solo color values
            '),?)*' .
            '(\s*\})' . //match the end of the array
            '/s', $jsonConfig, $matches);
        $jsonConfig = '{' . $matches[0] . '}';


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
