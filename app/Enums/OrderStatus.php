<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Commandee = 'commandee';
    case Payee = 'payee';
    case Achetee = 'achetee';
    case Expediee = 'expediee';
    case EnLivraison = 'en_livraison';
    case Livree = 'livree';
    case Annulee = 'annulee';

    public function label(): string
    {
        return match ($this) {
            self::Commandee => 'Commandée',
            self::Payee => 'Payée',
            self::Achetee => 'Achetée sur le site',
            self::Expediee => 'Expédiée',
            self::EnLivraison => 'En livraison locale',
            self::Livree => 'Livrée',
            self::Annulee => 'Annulée',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Commandee => 'bg-yellow-100 text-yellow-800',
            self::Payee => 'bg-blue-100 text-blue-800',
            self::Achetee => 'bg-indigo-100 text-indigo-800',
            self::Expediee => 'bg-purple-100 text-purple-800',
            self::EnLivraison => 'bg-orange-100 text-orange-800',
            self::Livree => 'bg-green-100 text-green-800',
            self::Annulee => 'bg-red-100 text-red-800',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Commandee => 'clipboard-list',
            self::Payee => 'credit-card',
            self::Achetee => 'shopping-cart',
            self::Expediee => 'truck',
            self::EnLivraison => 'map-pin',
            self::Livree => 'check-circle',
            self::Annulee => 'x-circle',
        };
    }

    public function step(): int
    {
        return match ($this) {
            self::Commandee => 1,
            self::Payee => 2,
            self::Achetee => 3,
            self::Expediee => 4,
            self::EnLivraison => 5,
            self::Livree => 6,
            self::Annulee => 0,
        };
    }

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Commandee => [self::Payee, self::Annulee],
            self::Payee => [self::Achetee, self::Annulee],
            self::Achetee => [self::Expediee],
            self::Expediee => [self::EnLivraison],
            self::EnLivraison => [self::Livree],
            self::Livree => [],
            self::Annulee => [],
        };
    }

    public function canTransitionTo(self $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions());
    }

    public static function activeStatuses(): array
    {
        return [
            self::Commandee,
            self::Payee,
            self::Achetee,
            self::Expediee,
            self::EnLivraison,
        ];
    }
}
