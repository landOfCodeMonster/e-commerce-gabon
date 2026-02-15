<?php

namespace App\Services\Scraper;

use App\DTOs\ScrapedProduct;

interface ScraperInterface
{
    public function scrape(string $url): ScrapedProduct;
    public function supports(string $url): bool;
}
