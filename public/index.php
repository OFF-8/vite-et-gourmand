<?php

declare(strict_types=1);

require dirname(__DIR__) . '/includes/bootstrap.php';
require dirname(__DIR__) . '/includes/header.php';

use App\Database;
use App\Mailer;
use App\MongoStore;

?>
<h1>Vite & Gourmand</h1>
<p>Traiteur à Bordeaux pour vos évènements.</p>

<?php if (getenv('APP_ENV') === 'local'): ?>
    <ul>
        <?php
        $checks = [
            'MySQL' => function (): void {
                (new Database())->check();
            },
            'MongoDB' => function (): void {
                (new MongoStore())->check();
            },
            'Mail' => function (): void {
                (new Mailer())->send(
                    (string) getenv('CONTACT_EMAIL'),
                    'Test Mailpit',
                    'Socle PROJ-01'
                );
            },
        ];

        foreach ($checks as $label => $check) {
            try {
                $check();
                echo '<li>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ' : OK</li>';
            } catch (Throwable $exception) {
                echo '<li>' . htmlspecialchars($label . ' : ' . $exception->getMessage(), ENT_QUOTES, 'UTF-8') . '</li>';
            }
        }
        ?>
    </ul>
<?php endif; ?>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>