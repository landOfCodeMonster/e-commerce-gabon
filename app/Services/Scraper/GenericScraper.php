<?php

namespace App\Services\Scraper;

use App\DTOs\ScrapedProduct;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class GenericScraper implements ScraperInterface
{
    public function supports(string $url): bool
    {
        return true;
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

        $title = $this->extractMeta($crawler, ['og:title', 'twitter:title'])
            ?? $this->extractTag($crawler, 'title');

        $price = $this->extractPrice($crawler);
        $currency = $this->extractMeta($crawler, ['og:price:currency', 'product:price:currency']) ?? 'USD';
        $image = $this->extractMeta($crawler, ['og:image', 'twitter:image']);

        $sourceSite = parse_url($url, PHP_URL_HOST);

        return new ScrapedProduct(
            title: $title,
            price: $price,
            currency: $currency,
            imageUrl: $image,
            sourceUrl: $url,
            sourceSite: $sourceSite,
        );
    }

    protected function extractMeta(Crawler $crawler, array $properties): ?string
    {
        foreach ($properties as $property) {
            try {
                $value = $crawler->filter("meta[property='{$property}']")->attr('content');
                if ($value) return trim($value);
            } catch (\Exception $e) {}

            try {
                $value = $crawler->filter("meta[name='{$property}']")->attr('content');
                if ($value) return trim($value);
            } catch (\Exception $e) {}
        }

        return null;
    }

    protected function extractTag(Crawler $crawler, string $tag): ?string
    {
        try {
            return trim($crawler->filter($tag)->first()->text());
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function extractPrice(Crawler $crawler): ?float
    {
        $priceStr = $this->extractMeta($crawler, ['og:price:amount', 'product:price:amount']);

        if ($priceStr) {
            return $this->parsePrice($priceStr);
        }

        $priceSelectors = [
            '[data-price]',
            '.price',
            '.product-price',
            '#price',
            '[itemprop="price"]',
        ];

        foreach ($priceSelectors as $selector) {
            try {
                $node = $crawler->filter($selector)->first();
                $priceAttr = $node->attr('content') ?? $node->attr('data-price') ?? $node->text();
                if ($priceAttr) {
                    $parsed = $this->parsePrice($priceAttr);
                    if ($parsed !== null) return $parsed;
                }
            } catch (\Exception $e) {}
        }

        return null;
    }

    protected function parsePrice(string $priceStr): ?float
    {
        $cleaned = preg_replace('/[^\d.,]/', '', $priceStr);
        $cleaned = str_replace(',', '.', $cleaned);

        if (substr_count($cleaned, '.') > 1) {
            $cleaned = str_replace('.', '', substr($cleaned, 0, strrpos($cleaned, '.'))) . substr($cleaned, strrpos($cleaned, '.'));
        }

        $value = (float) $cleaned;
        return $value > 0 ? $value : null;
    }
}
