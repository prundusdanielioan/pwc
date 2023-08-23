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
        $origin = strtoupper($origin); // Convert $origin to uppercase
        $destination = strtoupper($destination); // Convert $destination to uppercase
        $isValidOrigin = preg_match('/^[A-Z]{3}$/', $origin) === 1;
        $isValidDestination = preg_match('/^[A-Z]{3}$/', $destination) === 1;

        // Check if both $origin and $destination are valid
        if (!$isValidOrigin || !$isValidDestination) {
            // Handle validation errors, e.g., return an error response
            return new JsonResponse(['error' => 'Invalid origin or destination, country name should be cca3'], 400);
        }
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
