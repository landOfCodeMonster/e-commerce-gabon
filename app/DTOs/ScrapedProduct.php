<?php

namespace App\DTOs;

class ScrapedProduct
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?float $price,
        public readonly ?string $currency,
        public readonly ?string $imageUrl,
        public readonly string $sourceUrl,
        public readonly string $sourceSite,
    ) {}
}
