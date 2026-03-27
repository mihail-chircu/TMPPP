<?php

namespace App\Services\Cart;

/**
 * Command Pattern — Invoker.
 *
 * Executes commands and maintains a history stack,
 * enabling undo of the last operation.
 */
class CartCommandInvoker
{
    /** @var CartCommand[] */
    private array $history = [];

    /**
     * Execute a command and add it to history.
     */
    public function execute(CartCommand $command): mixed
    {
        $result = $command->execute();
        $this->history[] = $command;

        return $result;
    }

    /**
     * Undo the last executed command.
     */
    public function undoLast(): ?string
    {
        $command = array_pop($this->history);

        if (! $command) {
            return null;
        }

        $command->undo();

        return $command->getDescription();
    }

    /**
     * Get the history of executed commands.
     */
    public function getHistory(): array
    {
        return array_map(fn (CartCommand $c) => $c->getDescription(), $this->history);
    }
}
