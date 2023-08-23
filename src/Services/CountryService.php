<?php
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CountryService
{
    private HttpClientInterface $httpClient;
    private string $countriesDataUrl;

    public function __construct(HttpClientInterface $httpClient, string $countriesDataUrl)
    {
        $this->httpClient = $httpClient;
        $this->countriesDataUrl = $countriesDataUrl;
    }

    public function fetchCountriesData(): array
    {
        try {
            // Send an HTTP GET request to retrieve country data
            $response = $this->httpClient->request('GET', $this->countriesDataUrl);

            // Convert the response to an array
            $data = $response->toArray();

            // Transform the data into the desired format
            return $this->transformDataToCountries($data);
        } catch (TransportExceptionInterface $e) {

            return [];
        }
    }

    private function transformDataToCountries(array $data): array
    {
        $countries = [];

        foreach ($data as $country) {
            // Ensure the necessary data fields exist in the fetched data
            if (isset($country['cca3']) && isset($country['borders'])) {
                $countries[strtoupper($country['cca3'])] = [
                    'borders' => $country['borders'],
                ];
            }
        }

        return $countries;
    }
}
