<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeModule extends BaseCommand
{
    protected $group       = 'Generators';
    protected $name        = 'make:module';
    protected $description = 'Generate HMVC module with optional CRUD and Model';

    public function run(array $params)
    {
        $module = $params[0] ?? null;

        if (!$module) {
            CLI::error('Nama modul wajib diisi');
            return;
        }

        $uc         = ucfirst($module);
        $lower      = strtolower($module);
        $controller = "{$uc}Controller";
        $layout     = "{$lower}_layout";
        $basePath   = APPPATH . "Modules/{$uc}";

        /* ================= CRUD ================= */

        CLI::write('Generate CRUD? (yes/no) [no]: ', 'yellow');
        $crudInput = strtolower(trim(CLI::input() ?: 'no'));
        $withCrud  = in_array($crudInput, ['y', 'yes'], true);

        /* ================= MODEL ================= */

        CLI::write('Add Model? (yes/no) [no]: ', 'yellow');
        $modelInput = strtolower(trim(CLI::input() ?: 'no'));
        $withModel  = in_array($modelInput, ['y', 'yes'], true);

        $modelName = null;
        if ($withModel) {
            CLI::write('Model name (ex: UserModel): ', 'yellow');
            $modelName = trim(CLI::input());

            if (!$modelName || !preg_match('/^[A-Z][A-Za-z0-9_]+$/', $modelName)) {
                CLI::error('Nama model tidak valid');
                return;
            }
        }

        /* ================= FOLDERS ================= */

        foreach (
            [
                "{$basePath}/Config",
                "{$basePath}/Controllers",
                "{$basePath}/Models",
                "{$basePath}/Views/layouts",
                "{$basePath}/Database/Migrations",
                "{$basePath}/Database/Seeds",
            ] as $dir
        ) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        /* ================= ROUTES ================= */

        file_put_contents(
            "{$basePath}/Config/Routes.php",
            $this->routes($lower, $uc, $controller, $withCrud)
        );

        /* ================= CONTROLLER ================= */

        file_put_contents(
            "{$basePath}/Controllers/{$controller}.php",
            $this->controller($uc, $lower, $controller, $withCrud, $modelName)
        );

        /* ================= MODEL ================= */

        if ($withModel) {
            $modelPath = "{$basePath}/Models/{$modelName}.php";

            if (!file_exists($modelPath)) {
                file_put_contents(
                    $modelPath,
                    $this->model($uc, $modelName)
                );
            }
        }

        /* ================= LAYOUT ================= */

        file_put_contents(
            "{$basePath}/Views/layouts/{$layout}.php",
            $this->layout($uc)
        );

        /* ================= VIEWS ================= */

        $this->views($basePath, $uc, $layout, $lower, $withCrud);

        /* ================= PSR-4 ================= */

        $this->registerNamespaceSafe($uc);

        /* ================= DONE ================= */

        CLI::write("Module {$uc} berhasil dibuat", 'green');
        CLI::write("CRUD  : " . ($withCrud ? 'YES' : 'NO'), 'yellow');
        CLI::write("MODEL : " . ($withModel ? $modelName : 'NO'), 'yellow');
        CLI::write("Route : /{$lower}", 'cyan');
    }

    /* =====================================================
     * ROUTES
     * =================================================== */

    private function routes(string $lower, string $uc, string $controller, bool $crud): string
    {
        $code = <<<PHP
<?php

use CodeIgniter\\Config\\Services;

\$routes = Services::routes();

\$routes->group('{$lower}', ['namespace' => 'Modules\\{$uc}\\Controllers'], function(\$routes) {
    \$routes->get('/', '{$controller}::index');
PHP;

        if ($crud) {
            $code .= <<<PHP

    \$routes->get('create', '{$controller}::create');
    \$routes->post('/', '{$controller}::store');
    \$routes->get('(:num)', '{$controller}::show/\$1');
    \$routes->get('(:num)/edit', '{$controller}::edit/\$1');
    \$routes->post('(:num)', '{$controller}::update/\$1');
    \$routes->post('(:num)/delete', '{$controller}::delete/\$1');
PHP;
        }

        return $code . "\n});";
    }

    /* =====================================================
     * CONTROLLER
     * =================================================== */

    private function controller(
        string $uc,
        string $lower,
        string $controller,
        bool $crud,
        ?string $modelName
    ): string {
        $modelUse = $modelName
            ? "use Modules\\{$uc}\\Models\\{$modelName};\n"
            : '';

        $modelProp = $modelName
            ? "    protected \${$lower}Model;\n"
            : '';

        $modelInit = $modelName
            ? "\n    public function __construct()\n    {\n        \$this->{$lower}Model = new {$modelName}();\n    }\n"
            : '';

        $methods = <<<PHP
    public function index()
    {
        return view('Modules\\{$uc}\\Views\\index', [
            'title' => '{$uc}',
            'menu'  => [
                'index'  => base_url('{$lower}'),
                'create' => base_url('{$lower}/create'),
            ]
        ]);
    }
PHP;

        if ($crud) {
            $methods .= <<<PHP

    public function create()
    {
        return view('Modules\\{$uc}\\Views\\create', [
            'title' => 'Create {$uc}',
            'menu'  => ['index' => base_url('{$lower}')]
        ]);
    }

    public function store()
    {
        return redirect()->back()->with('success', 'Saved (static)');
    }

    public function show(int \$id)
    {
        return view('Modules\\{$uc}\\Views\\detail', [
            'title' => 'Detail {$uc}',
            'id'    => \$id,
            'menu'  => [
                'index' => base_url('{$lower}'),
                'edit'  => base_url('{$lower}/' . \$id . '/edit'),
            ]
        ]);
    }

    public function edit(int \$id)
    {
        return view('Modules\\{$uc}\\Views\\edit', [
            'title' => 'Edit {$uc}',
            'id'    => \$id,
            'menu'  => [
                'index'  => base_url('{$lower}'),
                'detail' => base_url('{$lower}/' . \$id),
            ]
        ]);
    }

    public function update(int \$id)
    {
        return redirect()->back()->with('success', 'Updated (static)');
    }

    public function delete(int \$id)
    {
        return redirect()->to(base_url('{$lower}'))->with('success', 'Deleted (static)');
    }
PHP;
        }

        return <<<PHP
<?php

namespace Modules\\{$uc}\\Controllers;

{$modelUse}
use App\\Controllers\\BaseController;

class {$controller} extends BaseController
{
{$modelProp}{$modelInit}
{$methods}
}
PHP;
    }

    /* =====================================================
     * MODEL
     * =================================================== */

    private function model(string $uc, string $modelName): string
    {
        return <<<PHP
<?php

namespace Modules\\{$uc}\\Models;

use CodeIgniter\\Model;

class {$modelName} extends Model
{
    protected \$table      = '';
    protected \$primaryKey = 'id';

    protected \$allowedFields = [];
}
PHP;
    }

    /* =====================================================
     * VIEWS
     * =================================================== */

    private function views(string $basePath, string $uc, string $layout, string $lower, bool $crud)
    {
        file_put_contents("{$basePath}/Views/index.php", <<<PHP
<?= \$this->extend('Modules\\{$uc}\\Views\\layouts\\{$layout}') ?>
<?= \$this->section('content') ?>
<div class="container">
    <h1>{$uc}</h1>
</div>
<?= \$this->endSection() ?>
PHP);

        if (!$crud) return;

        foreach (['create', 'edit', 'detail'] as $view) {
            file_put_contents("{$basePath}/Views/{$view}.php", <<<PHP
<?= \$this->extend('Modules\\{$uc}\\Views\\layouts\\{$layout}') ?>
<?= \$this->section('content') ?>
<div class="container">
    <h1>{$view} {$uc}</h1>
</div>
<?= \$this->endSection() ?>
PHP);
        }
    }

    /* =====================================================
     * LAYOUT
     * =================================================== */

    private function layout(string $uc): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc(\$title ?? '{$uc}') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand"><?= esc(\$title ?? '{$uc}') ?></span>

        <?php if (!empty(\$menu)): ?>
            <div class="navbar-nav ms-auto flex-row gap-3">
                <?php foreach (\$menu as \$label => \$url): ?>
                    <a class="nav-link text-white" href="<?= esc(\$url) ?>">
                        <?= ucfirst(\$label) ?>
                    </a>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</nav>

<main class="py-4">
    <?= \$this->renderSection('content') ?>
</main>

</body>
</html>
HTML;
    }

    /* =====================================================
     * PSR-4 SAFE
     * =================================================== */

    private function registerNamespaceSafe(string $module): void
    {
        $file = APPPATH . 'Config/Autoload.php';
        $content = file_get_contents($file);

        $namespace = "Modules\\{$module}";

        if (strpos($content, "'{$namespace}'") !== false) {
            return;
        }

        $pattern = '/public \\$psr4\\s*=\\s*\\[(.*?)\\n\\s*\\];/s';

        if (!preg_match($pattern, $content, $matches)) {
            return;
        }

        $insert = "        '{$namespace}' => APPPATH . 'Modules/{$module}',\n";
        $replacement = "public \$psr4 = [{$matches[1]}\n{$insert}    ];";

        file_put_contents($file, preg_replace($pattern, $replacement, $content));
    }
}
