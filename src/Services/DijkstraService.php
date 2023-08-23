<?php
class Dijkstra
{
    private array $graph = [];

    public function __construct(array $graph)
    {
        $this->graph = $graph;
    }

    public function shortestPath($start, $end)
    {
        $visited = [];
        $distance = [];
        $previous = [];

        foreach ($this->graph as $vertex => $edges) {
            $distance[$vertex] = INF;
            $previous[$vertex] = null;
        }

        $distance[$start] = 0;

        while ($vertex = $this->getShortestUnvisited($distance, $visited)) {
            if ($vertex === $end) {
                return $this->buildPath($end, $previous);
            }

            $visited[] = $vertex;

            if (!isset($this->graph[$vertex])) {
                continue; // Skip if the vertex has no neighbors
            }

            foreach ($this->graph[$vertex] as $neighbor => $weight) {
                $alt = $distance[$vertex] + $weight;
                if ($alt < $distance[$neighbor]) {
                    $distance[$neighbor] = $alt;
                    $previous[$neighbor] = $vertex;
                }
            }
        }

        return [];
    }

    private function getShortestUnvisited(array $distance, array $visited)
    {
        $shortest = INF;
        $shortestVertex = null;

        foreach ($distance as $vertex => $dist) {
            if (!in_array($vertex, $visited) && $dist < $shortest) {
                $shortest = $dist;
                $shortestVertex = $vertex;
            }
        }

        return $shortestVertex;
    }

    private function buildPath($end, array $previous)
    {
        $path = [];
        while (!is_null($end)) {
            array_unshift($path, $end);
            $end = $previous[$end];
        }
        return $path;
    }
}
