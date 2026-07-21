<?php

declare(strict_types=1);

/**
 * @var list<array{
 *     type: string,
 *     message: string
 * }> $flashMessages
 */
?>

<?php if ($flashMessages !== []): ?>
    <section
        aria-label="Messages de notification"
    >
        <?php foreach ($flashMessages as $flashMessage): ?>
            <div
                class="alert alert-<?= escape($flashMessage['type']) ?>"
                role="alert"
            >
                <?= escape($flashMessage['message']) ?>
            </div>
        <?php endforeach; ?>
    </section>
<?php endif; ?>