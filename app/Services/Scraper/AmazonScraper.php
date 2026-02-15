<?php

namespace App\Services\Scraper;

use App\DTOs\ScrapedProduct;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class AmazonScraper implements ScraperInterface
{
    public function supports(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        return str_contains($host, 'amazon.');
    }

    public function scrape(string $url): ScrapedProduct
    {
        $client = HttpClient::create([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
                'Accept' => 'text/html,application/xhtml+xml',
            ],
            'timeout' => 15,
        ]);

        $response = $client->request('GET', $url);
        $html = $response->getContent();
        $crawler = new Crawler($html);

        $title = $this->extractTitle($crawler);
        $price = $this->extractPrice($crawler);
        $image = $this->extractImage($crawler);
        $currency = $this->detectCurrency($url);

        return new ScrapedProduct(
            title: $title,
            price: $price,
            currency: $currency,
            imageUrl: $image,
            sourceUrl: $url,
            sourceSite: parse_url($url, PHP_URL_HOST),
        );
    }

    private function extractTitle(Crawler $crawler): ?string
    {
        $selectors = ['#productTitle', '#title span', 'h1.product-title-word-break'];

        foreach ($selectors as $selector) {
            try {
                $text = trim($crawler->filter($selector)->first()->text());
                if ($text) return $text;
            } catch (\Exception $e) {}
        }

        return null;
    }

    private function extractPrice(Crawler $crawler): ?float
    {
        $selectors = [
            '.a-price .a-offscreen',
            '#priceblock_ourprice',
            '#priceblock_dealprice',
            'span.a-price-whole',
        ];

        foreach ($selectors as $selector) {
            try {
                $text = trim($crawler->filter($selector)->first()->text());
                $cleaned = preg_replace('/[^\d.,]/', '', $text);
                $cleaned = str_replace(',', '.', $cleaned);
                $value = (float) $cleaned;
                if ($value > 0) return $value;
            } catch (\Exception $e) {}
        }

        return null;
    }

    private function extractImage(Crawler $crawler): ?string
    {
        $selectors = ['#landingImage', '#imgBlkFront', '#main-image'];

        foreach ($selectors as $selector) {
            try {
                $src = $crawler->filter($selector)->first()->attr('src');
                if ($src) return $src;
            } catch (\Exception $e) {}
        }

        return null;
    }

    private function detectCurrency(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        return match (true) {
            str_contains($host, 'amazon.fr') => 'EUR',
            str_contains($host, 'amazon.de') => 'EUR',
            str_contains($host, 'amazon.co.uk') => 'GBP',
            str_contains($host, 'amazon.co.jp') => 'JPY',
            default => 'USD',
        };
    }
}
