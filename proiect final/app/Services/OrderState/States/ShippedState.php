<?php

namespace App\Services\OrderState\States;

use App\Services\OrderState\OrderState;

class ShippedState extends OrderState
{
    public function getStatus(): string
    {
        return 'shipped';
    }

    public function getLabel(): string
    {
        return 'Expediată';
    }

    public function getColor(): string
    {
        return 'purple';
    }

    public function allowedTransitions(): array
    {
        return ['delivered'];
    }
}
