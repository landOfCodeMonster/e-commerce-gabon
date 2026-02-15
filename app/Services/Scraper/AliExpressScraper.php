<?php

namespace App\Services\Scraper;

use App\DTOs\ScrapedProduct;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class AliExpressScraper implements ScraperInterface
{
    public function supports(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        return str_contains($host, 'aliexpress.');
    }

    public function scrape(string $url): ScrapedProduct
    {
        $client = HttpClient::create([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
            ],
            'timeout' => 15,
        ]);

        $response = $client->request('GET', $url);
        $html = $response->getContent();
        $crawler = new Crawler($html);

        $title = $this->extractMeta($crawler, 'og:title')
            ?? $this->extractText($crawler, 'h1');

        $price = $this->extractPriceFromMeta($crawler)
            ?? $this->extractPriceFromPage($crawler);

        $image = $this->extractMeta($crawler, 'og:image');

        return new ScrapedProduct(
            title: $title,
            price: $price,
            currency: 'USD',
            imageUrl: $image,
            sourceUrl: $url,
            sourceSite: parse_url($url, PHP_URL_HOST),
        );
    }

    private function extractMeta(Crawler $crawler, string $property): ?string
    {
        try {
            return trim($crawler->filter("meta[property='{$property}']")->attr('content'));
        } catch (\Exception $e) {
            return null;
        }
    }

    private function extractText(Crawler $crawler, string $selector): ?string
    {
        try {
            return trim($crawler->filter($selector)->first()->text());
        } catch (\Exception $e) {
            return null;
        }
    }

    private function extractPriceFromMeta(Crawler $crawler): ?float
    {
        $priceStr = $this->extractMeta($crawler, 'og:price:amount');
        if ($priceStr) {
            $value = (float) preg_replace('/[^\d.]/', '', $priceStr);
            return $value > 0 ? $value : null;
        }
        return null;
    }

    private function extractPriceFromPage(Crawler $crawler): ?float
    {
        $selectors = ['.product-price-value', '.uniform-banner-box-price'];
        foreach ($selectors as $selector) {
            try {
                $text = $crawler->filter($selector)->first()->text();
                $value = (float) preg_replace('/[^\d.]/', '', $text);
                if ($value > 0) return $value;
            } catch (\Exception $e) {}
        }
        return null;
    }
}
