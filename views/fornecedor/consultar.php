<?php
require_once __DIR__ . '/../../controller/FornecedorController.php';
$dados = (new FornecedorController())->listar();
$h = static fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<div class="card shadow"><div class="card-body"><h3>Fornecedores <a class="btn btn-success float-right" href="?p=add/fornecedor">Cadastrar</a> <a class="btn btn-primary float-right mr-2" href="?p=pesquisar/fornecedor">Pesquisar</a></h3>
<div class="table-responsive"><table class="table table-striped"><thead><tr><th>ID</th><th>Razão Social</th><th>E-mail</th><th>Telefone</th><th>Ações</th></tr></thead><tbody>
<?php foreach ($dados as $d): ?><tr><td><?= $h($d['id']) ?></td><td><?= $h($d['razao_social']) ?></td><td><?= $h($d['email']) ?></td><td><?= $h($d['telefone']) ?></td><td><a class="btn btn-warning" href="?p=editar/fornecedor&amp;id=<?= $h($d['id']) ?>">Editar</a></td></tr><?php endforeach; ?>
</tbody></table></div></div></div>
