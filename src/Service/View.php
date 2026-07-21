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
    ) {
    }

    /**
     * Renders a template inside the main layout.
     *
     * @param array<string, mixed> $data
     */
    public function render(
        string $template,
        array $data = [],
        string $layout = 'layouts/base',
    ): string {
        $content = $this->renderTemplate($template, $data);
    
        return $this->renderTemplate($layout, [
            ...$data,
            'content' => $content,
            'flashMessages' => $this->flash->consume(),
        ]);
    }

    /**
     * Renders a PHP template and returns its generated HTML.
     *
     * @param array<string, mixed> $data
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

        extract($data, EXTR_SKIP);

        ob_start();

        require $templateFile;

        $content = ob_get_clean();

        if ($content === false) {
            throw new RuntimeException(
                sprintf('Unable to render template "%s".', $template)
            );
        }

        return $content;
    }
}