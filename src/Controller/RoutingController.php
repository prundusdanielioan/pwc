<?php

namespace App\Controller;

use CountryGraph;
use CountryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RoutingController extends AbstractController
{
    private CountryService $countryService;
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        // Instantiate CountryService with the HTTP client and the countries data URL
        $this->countryService = new CountryService($httpClient, $_ENV['COUNTRIES_DATA_URL']);
    }

    public function route(string $origin, string $destination): JsonResponse
    {
        // Fetch country data using the CountryService
        $countries = $this->countryService->fetchCountriesData();

        // Create a CountryGraph instance with the fetched country data
        $countryGraph = new CountryGraph($countries);

        // Get the route from origin to destination using the CountryGraph
        $route = $countryGraph->getRoute($origin, $destination);

        // Return the route as a JSON response
        return new JsonResponse($route);
    }
}
