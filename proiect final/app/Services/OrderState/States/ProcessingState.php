<?php

namespace App\Services\OrderState\States;

use App\Services\OrderState\OrderState;

class ProcessingState extends OrderState
{
    public function getStatus(): string
    {
        return 'processing';
    }

    public function getLabel(): string
    {
        return 'Se procesează';
    }

    public function getColor(): string
    {
        return 'blue';
    }

    public function allowedTransitions(): array
    {
        return ['shipped', 'cancelled'];
    }
}
