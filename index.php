<?php
declare(strict_types=1);

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

require_once __DIR__ . '/Conn.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/actions.php';
$routes = require __DIR__ . '/rotas.php';
$securityError = null;
try {
    csrf_secret();
} catch (RuntimeException $error) {
    $securityError = $error->getMessage();
}

$route = trim((string) ($_GET['p'] ?? 'home'));
$isNotFound = !array_key_exists($route, $routes);
if ($isNotFound) {
    http_response_code(404);
    $page = ['view' => 'views/not-found.php', 'title' => 'Página não encontrada'];
} else {
    $page = $routes[$route];
}

$pdo = null;
$dbError = null;
try {
    $pdo = (new Conn())->conectar();
} catch (Throwable $error) {
    error_log('Falha de conexão do Armazém Inteligente: ' . $error->getMessage());
    $dbError = 'Não foi possível conectar ao banco de dados. Confira a configuração e execute o script de instalação.';
}

$formErrors = [];
$oldInput = $_POST;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($isNotFound) {
            throw new InvalidArgumentException('A ação solicitada não existe.');
        }
        if (!$pdo) {
            throw new RuntimeException($dbError ?? 'Banco de dados indisponível.');
        }
        $result = handle_post($route, $pdo);
        set_flash('success', $result['message']);
        header('Location: ?p=' . rawurlencode($result['redirect']));
        exit;
    } catch (PDOException $error) {
        error_log('Falha em operação de banco do Armazém Inteligente: ' . $error->getMessage());
        $formErrors[] = $error->getCode() === '23000'
            ? 'Este registro já existe ou está sendo usado por outro item. Confira os dados e tente novamente.'
            : 'Não foi possível salvar as alterações. Confira o banco de dados e tente novamente.';
    } catch (InvalidArgumentException | RuntimeException $error) {
        $formErrors[] = $error->getMessage();
    } catch (Throwable $error) {
        error_log('Erro de formulário no Armazém Inteligente: ' . $error->getMessage());
        $formErrors[] = 'Ocorreu um erro inesperado. Tente novamente.';
    }
}

$flashMessage = $securityError === null ? take_flash() : null;
$activeGroup = match (true) {
    in_array($route, ['produtos', 'add/produto', 'movimentacoes'], true) => 'estoque',
    in_array($route, ['categorias', 'add/categoria'], true) => 'categorias',
    in_array($route, ['fornecedores', 'add/fornecedor'], true) => 'fornecedores',
    in_array($route, ['clientes', 'add/cliente'], true) => 'clientes',
    default => 'home',
};
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#18794e">
    <title><?= e($page['title']) ?> · Armazém Inteligente</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" aria-label="Navegação principal">
        <a class="brand" href="?p=home">
            <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
            <span>Armazém<span class="text-success">.</span></span>
        </a>
        <div class="nav-caption">Painel</div>
        <nav class="nav flex-column">
            <a class="nav-link <?= $activeGroup === 'home' ? 'active' : '' ?>" href="?p=home"><i class="bi bi-grid-1x2"></i> Visão geral</a>
        </nav>
        <div class="nav-caption">Operação</div>
        <nav class="nav flex-column">
            <a class="nav-link <?= $activeGroup === 'estoque' ? 'active' : '' ?>" href="?p=produtos"><i class="bi bi-boxes"></i> Estoque</a>
            <a class="nav-link <?= $route === 'movimentacoes' ? 'active' : '' ?>" href="?p=movimentacoes"><i class="bi bi-arrow-left-right"></i> Movimentações</a>
            <a class="nav-link <?= $activeGroup === 'categorias' ? 'active' : '' ?>" href="?p=categorias"><i class="bi bi-tags"></i> Categorias</a>
        </nav>
        <div class="nav-caption">Cadastros</div>
        <nav class="nav flex-column">
            <a class="nav-link <?= $activeGroup === 'fornecedores' ? 'active' : '' ?>" href="?p=fornecedores"><i class="bi bi-truck"></i> Fornecedores</a>
            <a class="nav-link <?= $activeGroup === 'clientes' ? 'active' : '' ?>" href="?p=clientes"><i class="bi bi-people"></i> Clientes</a>
        </nav>
        <div class="sidebar-bottom"><i class="bi bi-shield-check text-success me-1"></i> Seus cadastros ficam no banco da sua empresa.</div>
    </aside>

    <div class="main-area">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-light border mobile-menu" data-sidebar-toggle aria-label="Abrir menu" aria-expanded="false"><i class="bi bi-list"></i></button>
                <span class="small text-secondary d-none d-sm-inline">Gestão simples, estoque sob controle.</span>
            </div>
            <div class="d-flex align-items-center gap-2 small fw-semibold"><span class="rounded-circle <?= $dbError ? 'bg-danger' : 'bg-success' ?>" style="width:8px;height:8px"></span> <?= $dbError ? 'Banco indisponível' : 'Conectado ao banco' ?></div>
        </header>

        <main class="content">
            <div class="mb-4">
                <div class="eyebrow">Armazém inteligente de itens</div>
                <h1 class="page-title"><?= e($page['title']) ?></h1>
                <p class="page-subtitle">Acompanhe produtos, entradas e saídas em um só lugar.</p>
            </div>

            <?php if ($flashMessage): ?>
                <div class="alert alert-<?= e($flashMessage['type']) ?> alert-dismissible fade show" role="status">
                    <?= e($flashMessage['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            <?php endif; ?>

            <?php if ($securityError): ?>
                <div class="alert alert-warning" role="alert"><?= e($securityError) ?></div>
            <?php endif; ?>

            <?php if ($dbError): ?>
                <section class="surface p-4 p-lg-5">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="stat-icon flex-shrink-0"><i class="bi bi-database-exclamation"></i></div>
                        <div>
                            <h2 class="h5">Banco de dados ainda não configurado</h2>
                            <p class="text-secondary mb-3"><?= e($dbError) ?></p>
                            <ol class="text-secondary">
                                <li>Crie um banco MySQL e execute o arquivo <code>Procedure.txt</code>.</li>
                                <li>Configure <code>DB_HOST</code>, <code>DB_NAME</code>, <code>DB_USER</code> e <code>DB_PASS</code> no servidor.</li>
                                <li>Atualize esta página para testar novamente.</li>
                            </ol>
                            <p class="small text-secondary mb-0">Por segurança, os detalhes internos da conexão não são exibidos nesta tela.</p>
                        </div>
                    </div>
                </section>
            <?php elseif (!$isNotFound): ?>
                <?php foreach ($formErrors as $formError): ?>
                    <div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-circle me-2"></i><?= e($formError) ?></div>
                <?php endforeach; ?>
                <?php try { include __DIR__ . '/' . $page['view']; } catch (Throwable $error) {
                    error_log('Falha ao renderizar página ' . $route . ': ' . $error->getMessage());
                    echo '<div class="alert alert-danger">Não foi possível carregar os dados. Confirme se o script <code>Procedure.txt</code> foi executado corretamente.</div>';
                } ?>
            <?php else: ?>
                <?php include __DIR__ . '/' . $page['view']; ?>
            <?php endif; ?>

            <div class="footer-note">Armazém Inteligente · Controle claro para o dia a dia.</div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
