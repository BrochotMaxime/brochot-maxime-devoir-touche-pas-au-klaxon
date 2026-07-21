<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Stores temporary session messages displayed after a redirect.
 */
final class Flash
{
    private const SESSION_KEY = 'flash_messages';

    public function __construct(
        private readonly Session $session,
    ) {
    }

    public function success(string $message): void
    {
        $this->add('success', $message);
    }

    public function error(string $message): void
    {
        $this->add('danger', $message);
    }

    public function warning(string $message): void
    {
        $this->add('warning', $message);
    }

    public function info(string $message): void
    {
        $this->add('info', $message);
    }

    /**
     * Returns and removes all stored flash messages.
     *
     * @return list<array{
     *     type: string,
     *     message: string
     * }>
     */
    public function consume(): array
    {
        $messages = $this->session->get(
            self::SESSION_KEY,
            [],
        );

        $this->session->remove(self::SESSION_KEY);

        if (!is_array($messages)) {
            return [];
        }

        return array_values(
            array_filter(
                $messages,
                static fn (mixed $message): bool => is_array($message)
                    && isset($message['type'], $message['message'])
                    && is_string($message['type'])
                    && is_string($message['message']),
            ),
        );
    }

    private function add(string $type, string $message): void
    {
        $messages = $this->session->get(
            self::SESSION_KEY,
            [],
        );

        if (!is_array($messages)) {
            $messages = [];
        }

        $messages[] = [
            'type' => $type,
            'message' => $message,
        ];

        $this->session->set(
            self::SESSION_KEY,
            $messages,
        );
    }
}