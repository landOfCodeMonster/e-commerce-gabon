<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::whereNotIn('status', ['livree', 'annulee'])->count();
        $monthRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $totalProfit = Order::where('status', 'livree')->sum('profit_margin');
        $totalClients = User::where('role', 'client')->count();
        $recentOrders = Order::with('user')->latest()->limit(8)->get();

        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Payment::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');

        $revenueTotal = Payment::where('status', 'completed')->sum('amount');

        $conversionRate = $totalOrders > 0
            ? round(Order::where('status', 'livree')->count() / $totalOrders * 100, 1)
            : 0;

        // Revenue per month (last 6 months)
        $ordersPerMonth = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            $revenue = Payment::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            return [
                'month' => ucfirst($date->translatedFormat('M')),
                'total' => $revenue,
            ];
        });

        $maxMonthRevenue = $ordersPerMonth->max('total') ?: 1;

        // Orders by status
        $ordersByStatus = collect(OrderStatus::cases())
            ->filter(fn ($s) => $s !== OrderStatus::Annulee)
            ->map(fn ($status) => [
                'status' => $status,
                'count' => Order::where('status', $status->value)->count(),
            ]);

        $maxStatusCount = $ordersByStatus->max('count') ?: 1;

        return view('livewire.admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'monthRevenue',
            'totalProfit',
            'totalClients',
            'recentOrders',
            'todayOrders',
            'todayRevenue',
            'revenueTotal',
            'conversionRate',
            'ordersPerMonth',
            'maxMonthRevenue',
            'ordersByStatus',
            'maxStatusCount',
        ));
    }
}
