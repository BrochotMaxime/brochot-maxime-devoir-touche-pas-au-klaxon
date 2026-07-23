<?php

declare(strict_types=1);

namespace App\Service;

use RuntimeException;

/**
 * Renders PHP templates inside the shared application layout.
 */
final class View
{
    public function __construct(
        private readonly string $templatePath,
        private readonly Flash $flash,
        private readonly Csrf $csrf,
    ) {
    }

    /**
     * Renders a template inside the main layout.
     *
     * @param array<string, mixed> $data
     *
     * @throws RuntimeException When the template or layout file cannot be found.
     */
    public function render(
        string $template,
        array $data = [],
        string $layout = 'layouts/base',
    ): string {
        $templateData = [
            ...$data,
            'csrf' => $this->csrf,
        ];

        $content = $this->renderTemplate(
            $template,
            $templateData,
        );

        return $this->renderTemplate($layout, [
            ...$templateData,
            'content' => $content,
            'flashMessages' => $this->flash->consume(),
        ]);
    }

    /**
     * Renders a PHP template and returns its generated HTML.
     *
     * @param array<string, mixed> $data
     * 
     * @throws RuntimeException When the template file cannot be found.
     */
    private function renderTemplate(string $template, array $data): string
    {
        $templateFile = sprintf(
            '%s/%s.php',
            rtrim($this->templatePath, '/\\'),
            ltrim($template, '/\\'),
        );

        if (!is_file($templateFile)) {
            throw new RuntimeException(
                sprintf('Template "%s" was not found.', $template)
            );
        }

        // Prevent template data from overwriting variables already defined here.
        extract($data, EXTR_SKIP);

        ob_start();

        require $templateFile;

        return ob_get_clean();
    }
}