<?php

namespace App\Controller\Development;

use PhpParser\JsonDecoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class DesignController extends AbstractController
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    #[Route('/dev', name: 'app_development')]
    public function index(): Response
    {
        $routesArray =
            $this->getRoutesForController()->getIterator()->getArrayCopy();

        // Drop the index route
        unset($routesArray['app_development']);

        return $this->render('dev/index.html.twig', [
            'controller_name' => 'DesignController',
            'routes' => $routesArray
        ]);
    }

    #[Route('/dev/components', name: 'app_development_components')]
    public function components(): Response
    {
        return $this->render('dev/components.html.twig', [
            'controller_name' => 'DesignController',
        ]);
    }

    #[Route('/dev/colors', name: 'app_development_colors')]
    public function colors(): Response
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
        $jsonConfig = preg_replace('/([\w-]+)\s*:/', '"$1":', $jsonConfig); //fix keys
        preg_match('/' . //get out only the color array
            '("colors":\s*\{)' . //match the start of the array
            '((\s*("[\w-]*":)\s*)(' . //match array keys
                '(\{[^\}]*\})' . //match inner arrays
                '|'.
                '("[#\w-]*")' . //match solo color values
            '),?)*' .
            '(\s*\})' . //match the end of the array
            '/s', $jsonConfig, $matches);
        $jsonConfig = '{' . $matches[0] . '}';


        // Finally parse the tailwind.config.js file
        $parsedConfig = (new JsonDecoder)->decode($jsonConfig);

        // Get the colors from the config
        $colors = $parsedConfig['colors'];

        // Render the Twig template and pass the colors to it
        return $this->render('dev/colors.html.twig', [
            'controller_name' => 'DesignController',
            'colors' => $colors,
        ]);
    }

    private function getRoutesForController(): RouteCollection
    {
        $allRoutes = $this->router->getRouteCollection()->all();
        $managedRoutes = new RouteCollection();
        $selfClass = self::class;


        foreach ($allRoutes as $routeName => $route) {
            $controllerName = $route->getDefault('_controller');

            if (str_contains($controllerName, $selfClass)) {
                $managedRoutes->add($routeName, $route);
            }
        }

        return $managedRoutes;
    }
}
