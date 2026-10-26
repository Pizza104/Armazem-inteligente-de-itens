<?php
require_once __DIR__ . '/../../controller/FornecedorController.php';
$controller = new FornecedorController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || !($fornecedor = $controller->buscarPorId($id))) { echo '<div class="alert alert-danger">Fornecedor não encontrado.</div>'; return; }
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try { if ($controller->alterar()) { header('Location: ?p=fornecedores'); exit; } $erro = 'Nenhuma alteração foi salva.'; }
    catch (Throwable $e) { $erro = 'Não foi possível atualizar. Verifique a tabela fornecedor.'; }
    $fornecedor = $controller->buscarPorId($id) ?? $fornecedor;
}
$h = static fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<div class="card"><div class="card-header">Editar Fornecedor</div><div class="card-body">
<?php if ($erro): ?><div class="alert alert-danger"><?= $h($erro) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="id" value="<?= $h($fornecedor->getId()) ?>">
<div class="mb-3"><label class="form-label">Razão Social</label><input class="form-control" name="razao_social" required value="<?= $h($_POST['razao_social'] ?? $fornecedor->getRazaoSocial()) ?>"></div>
<div class="mb-3"><label class="form-label">E-mail</label><input class="form-control" type="email" name="email" required value="<?= $h($_POST['email'] ?? $fornecedor->getEmail()) ?>"></div>
<div class="mb-3"><label class="form-label">Telefone</label><input class="form-control" name="telefone" required value="<?= $h($_POST['telefone'] ?? $fornecedor->getTelefone()) ?>"></div>
<button class="btn btn-primary" type="submit">Atualizar</button> <a class="btn btn-secondary" href="?p=fornecedores">Cancelar</a></form></div></div>
