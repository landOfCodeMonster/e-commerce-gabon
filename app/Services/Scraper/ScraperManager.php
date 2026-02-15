<?php

namespace App\Services\Scraper;

use App\DTOs\ScrapedProduct;

class ScraperManager
{
    private array $scrapers;

    public function __construct()
    {
        $this->scrapers = [
            new AmazonScraper(),
            new AliExpressScraper(),
        ];
    }

    public function scrape(string $url): ScrapedProduct
    {
        foreach ($this->scrapers as $scraper) {
            if ($scraper->supports($url)) {
                try {
                    return $scraper->scrape($url);
                } catch (\Exception $e) {
                    // Fall through to generic scraper
                }
            }
        }

        return (new GenericScraper())->scrape($url);
    }
}
