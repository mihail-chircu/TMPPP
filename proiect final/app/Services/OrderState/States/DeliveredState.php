<?php

namespace App\Services\OrderState\States;

use App\Services\OrderState\OrderState;

class DeliveredState extends OrderState
{
    public function getStatus(): string
    {
        return 'delivered';
    }

    public function getLabel(): string
    {
        return 'Livrată';
    }

    public function getColor(): string
    {
        return 'green';
    }

    public function allowedTransitions(): array
    {
        return []; // Final state — no further transitions
    }
}
