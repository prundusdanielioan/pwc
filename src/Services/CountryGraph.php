<?php

class CountryGraph
{
    private array $graph = [];

    public function __construct(array $countries)
    {
        $this->buildGraph($countries);
    }

    private function buildGraph(array $countries)
    {
        foreach ($countries as $country => $data) {
            $this->graph[$country] = [];

            foreach ($data['borders'] as $border) {
                if (isset($countries[$border])) {
                    $this->graph[$country][$border] = 1; // Assuming uniform edge weight of 1
                }
            }
        }
    }

    public function getRoute($origin, $destination): array
    {
        $dijkstra = new Dijkstra($this->graph);
        return $dijkstra->shortestPath($origin, $destination);
    }
}