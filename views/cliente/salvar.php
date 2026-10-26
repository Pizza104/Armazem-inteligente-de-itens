<?php
require_once __DIR__ . '/../../controller/ClienteController.php';
$controller = new ClienteController();
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($controller->salvar()) { header('Location: ?p=clientes'); exit; }
        $erro = 'Não foi possível cadastrar o cliente.';
    } catch (Throwable $e) { $erro = 'Erro ao cadastrar. Verifique se a tabela cliente existe.'; }
}
?>
<div class="card"><div class="card-header">Novo Cliente</div><div class="card-body">
<?php if ($erro): ?><div class="alert alert-danger"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form method="post">
<div class="mb-3"><label class="form-label">Nome</label><input class="form-control" name="nome" required value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="mb-3"><label class="form-label">E-mail</label><input class="form-control" type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="mb-3"><label class="form-label">Telefone</label><input class="form-control" name="telefone" required value="<?= htmlspecialchars($_POST['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></div>
<button class="btn btn-success" type="submit">Salvar</button> <a class="btn btn-secondary" href="?p=clientes">Cancelar</a></form></div></div>
