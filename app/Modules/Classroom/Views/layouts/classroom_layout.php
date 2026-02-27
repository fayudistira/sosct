<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Classroom') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand"><?= esc($title ?? 'Classroom') ?></span>

        <?php if (!empty($menu)): ?>
            <div class="navbar-nav ms-auto flex-row gap-3">
                <?php foreach ($menu as $label => $url): ?>
                    <a class="nav-link text-white" href="<?= esc($url) ?>">
                        <?= ucfirst($label) ?>
                    </a>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</nav>

<main class="py-4">
    <?= $this->renderSection('content') ?>
</main>

</body>
</html>