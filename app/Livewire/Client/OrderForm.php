<?php

namespace App\Livewire\Client;

use App\DTOs\ScrapedProduct;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Services\CurrencyConverter;
use App\Services\Scraper\ScraperManager;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class OrderForm extends Component
{
    public string $url = '';
    public array $items = [];
    public string $notes = '';

    // Current item being added
    public ?string $scrapedTitle = null;
    public ?float $scrapedPrice = null;
    public ?string $scrapedCurrency = null;
    public ?string $scrapedImage = null;
    public ?string $sourceSite = null;
    public int $quantity = 1;
    public string $color = '';
    public string $size = '';
    public bool $scrapeSuccess = false;
    public bool $scraping = false;
    public bool $manualEntry = false;

    public function scrapeUrl(): void
    {
        $this->validate(['url' => 'required|url']);
        $this->resetScrapeState();
        $this->scraping = true;

        try {
            $result = app(ScraperManager::class)->scrape($this->url);
            $this->scrapedTitle = $result->title;
            $this->scrapedPrice = $result->price;
            $this->scrapedCurrency = $result->currency;
            $this->scrapedImage = $result->imageUrl;
            $this->sourceSite = $result->sourceSite;
            $this->scrapeSuccess = true;

            if (!$this->scrapedTitle && !$this->scrapedPrice) {
                $this->manualEntry = true;
                $this->addError('url', 'Impossible de récupérer les informations. Veuillez les saisir manuellement.');
            }
        } catch (\Exception $e) {
            $this->manualEntry = true;
            $this->addError('url', 'Erreur lors de l\'analyse du lien. Veuillez saisir les informations manuellement.');
        }

        $this->scraping = false;
    }

    public function addItem(): void
    {
        $this->validate([
            'url' => 'required|url',
            'scrapedTitle' => 'required|string|min:2',
            'scrapedPrice' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
        ]);

        $currency = $this->scrapedCurrency ?? 'USD';
        $convertedPrice = CurrencyConverter::toXAF($this->scrapedPrice, $currency);

        $this->items[] = [
            'source_url' => $this->url,
            'source_site' => $this->sourceSite ?? parse_url($this->url, PHP_URL_HOST),
            'scraped_title' => $this->scrapedTitle,
            'scraped_price' => $this->scrapedPrice,
            'scraped_currency' => $currency,
            'scraped_image' => $this->scrapedImage,
            'quantity' => $this->quantity,
            'color' => $this->color,
            'size' => $this->size,
            'converted_price' => $convertedPrice,
        ];

        $this->resetForm();
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submitOrder(): void
    {
        if (empty($this->items)) {
            $this->addError('items', 'Veuillez ajouter au moins un article.');
            return;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'reference' => Order::generateReference(),
            'status' => OrderStatus::Commandee,
            'currency' => Setting::get('default_currency', 'XAF'),
            'notes' => $this->notes,
        ]);

        foreach ($this->items as $item) {
            $order->items()->create($item);
        }

        $order->calculateTotals();

        // Log initial status
        $order->statusHistories()->create([
            'new_status' => OrderStatus::Commandee->value,
            'changed_by' => auth()->id(),
            'comment' => 'Commande créée',
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewOrderNotification($order));

        session()->flash('success', 'Commande #' . $order->reference . ' créée avec succès !');
        $this->redirect(route('client.orders.show', $order), navigate: true);
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->items)->sum(fn ($item) => ($item['converted_price'] ?? $item['scraped_price']) * $item['quantity']);
    }

    public function getServiceFeePercentProperty(): float
    {
        return (float) Setting::get('default_service_fee_percent', 10);
    }

    public function getServiceFeeProperty(): float
    {
        return round($this->subtotal * ($this->serviceFeePercent / 100), 0);
    }

    public function getEstimatedTotalProperty(): float
    {
        return $this->subtotal + $this->serviceFee;
    }

    private function resetForm(): void
    {
        $this->url = '';
        $this->resetScrapeState();
        $this->quantity = 1;
        $this->color = '';
        $this->size = '';
    }

    private function resetScrapeState(): void
    {
        $this->scrapedTitle = null;
        $this->scrapedPrice = null;
        $this->scrapedCurrency = null;
        $this->scrapedImage = null;
        $this->sourceSite = null;
        $this->scrapeSuccess = false;
        $this->manualEntry = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.client.order-form');
    }
}
