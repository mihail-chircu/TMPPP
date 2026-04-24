<?php

namespace App\Services\Cart;

use Illuminate\Support\Facades\Session;

/**
 * Command Pattern — Invoker.
 *
 * Executes commands and persists them to the session so that a
 * customer can undo the last cart operation on a subsequent HTTP
 * request. Commands are serialized as strings, so they must only
 * hold scalar state (no Eloquent models).
 */
class CartCommandInvoker
{
    private const SESSION_KEY = 'cart.command_history';

    private const MAX_HISTORY = 10;

    /**
     * Execute a command and push it to the session-backed history.
     */
    public function execute(CartCommand $command): mixed
    {
        $result = $command->execute();

        $history = Session::get(self::SESSION_KEY, []);
        $history[] = serialize($command);

        if (count($history) > self::MAX_HISTORY) {
            $history = array_slice($history, -self::MAX_HISTORY);
        }

        Session::put(self::SESSION_KEY, $history);

        return $result;
    }

    /**
     * Undo the last executed command.
     * Returns the human-readable description of what was reversed,
     * or null if there is nothing to undo.
     */
    public function undoLast(): ?string
    {
        $history = Session::get(self::SESSION_KEY, []);
        $serialized = array_pop($history);

        if (! $serialized) {
            return null;
        }

        /** @var CartCommand $command */
        $command = unserialize($serialized);
        $command->undo();

        Session::put(self::SESSION_KEY, $history);

        return $command->getDescription();
    }

    /**
     * True if there is at least one command that can be undone.
     */
    public function canUndo(): bool
    {
        return count(Session::get(self::SESSION_KEY, [])) > 0;
    }

    /**
     * Descriptions of all commands currently in history.
     *
     * @return string[]
     */
    public function getHistory(): array
    {
        $history = Session::get(self::SESSION_KEY, []);

        return array_map(
            function (string $serialized): string {
                /** @var CartCommand $command */
                $command = unserialize($serialized);

                return $command->getDescription();
            },
            $history,
        );
    }

    /**
     * Wipe the command history — called after checkout so that
     * post-checkout undo requests cannot resurrect items whose
     * cart no longer exists.
     */
    public function clearHistory(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
