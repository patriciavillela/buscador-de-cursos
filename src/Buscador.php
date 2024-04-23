<?php

namespace Alura\BuscadorDeCursos;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{
    private ClientInterface $httpClient;
    private Crawler $crawler;

    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    /**
     * @return array
     */
    public function buscar(string $url): array
    {
        try {
            $response = $this->httpClient->request('GET', $url);
        } catch (GuzzleException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        $html = $response->getBody();

        $this->crawler->addHtmlContent($html, 'UTF-8');

        $elementoCursos = $this->crawler->filter('span.card-curso__nome');
        $cursos = [];
        foreach ($elementoCursos as $curso) {
            $cursos[] = $curso->textContent;
        }
        return $cursos;
    }
}
