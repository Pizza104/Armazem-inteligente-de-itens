<?php
require_once __DIR__ . '/../../controller/FornecedorController.php';
$termo = trim($_GET['razao_social'] ?? '');
$dados = $termo === '' ? [] : (new FornecedorController())->pesquisar($termo);
$h = static fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<div class="card"><div class="card-body"><h3>Pesquisar fornecedor</h3><form method="get"><input type="hidden" name="p" value="pesquisar/fornecedor"><div class="form-inline mb-3"><input class="form-control mr-2" name="razao_social" placeholder="Digite a razão social" value="<?= $h($termo) ?>"><button class="btn btn-primary">Pesquisar</button></div></form>
<table class="table table-striped"><thead><tr><th>ID</th><th>Razão Social</th><th>E-mail</th><th>Telefone</th><th>Ações</th></tr></thead><tbody><?php foreach ($dados as $d): ?><tr><td><?= $h($d['id']) ?></td><td><?= $h($d['razao_social']) ?></td><td><?= $h($d['email']) ?></td><td><?= $h($d['telefone']) ?></td><td><a class="btn btn-warning" href="?p=editar/fornecedor&amp;id=<?= $h($d['id']) ?>">Editar</a></td></tr><?php endforeach; ?></tbody></table>
<?php if ($termo !== '' && !$dados): ?><p>Nenhum fornecedor encontrado.</p><?php endif; ?><a href="?p=fornecedores">Voltar à lista</a></div></div>
