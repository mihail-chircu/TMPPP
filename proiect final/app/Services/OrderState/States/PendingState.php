<?php

namespace App\Services\OrderState\States;

use App\Services\OrderState\OrderState;

class PendingState extends OrderState
{
    public function getStatus(): string
    {
        return 'pending';
    }

    public function getLabel(): string
    {
        return 'În așteptare';
    }

    public function getColor(): string
    {
        return 'yellow';
    }

    public function allowedTransitions(): array
    {
        return ['processing', 'cancelled'];
    }
}
