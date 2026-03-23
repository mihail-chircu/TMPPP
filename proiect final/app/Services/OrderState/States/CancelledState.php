<?php

namespace App\Services\OrderState\States;

use App\Services\OrderState\OrderState;

class CancelledState extends OrderState
{
    public function getStatus(): string
    {
        return 'cancelled';
    }

    public function getLabel(): string
    {
        return 'Anulată';
    }

    public function getColor(): string
    {
        return 'red';
    }

    public function allowedTransitions(): array
    {
        return []; // Final state — no further transitions
    }
}
