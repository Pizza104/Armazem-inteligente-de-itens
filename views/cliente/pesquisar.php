<?php
require_once __DIR__ . '/../../controller/ClienteController.php';
$termo = trim($_GET['nome'] ?? '');
$dados = $termo === '' ? [] : (new ClienteController())->pesquisar($termo);
$h = static fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<div class="card"><div class="card-body"><h3>Pesquisar cliente</h3><form method="get"><input type="hidden" name="p" value="pesquisar/cliente"><div class="form-inline mb-3"><input class="form-control mr-2" name="nome" placeholder="Digite o nome do cliente" value="<?= $h($termo) ?>"><button class="btn btn-primary">Pesquisar</button></div></form>
<table class="table table-striped"><thead><tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Ações</th></tr></thead><tbody><?php foreach ($dados as $d): ?><tr><td><?= $h($d['id']) ?></td><td><?= $h($d['nome']) ?></td><td><?= $h($d['email']) ?></td><td><?= $h($d['telefone']) ?></td><td><a class="btn btn-warning" href="?p=editar/cliente&amp;id=<?= $h($d['id']) ?>">Editar</a></td></tr><?php endforeach; ?></tbody></table>
<?php if ($termo !== '' && !$dados): ?><p>Nenhum cliente encontrado.</p><?php endif; ?><a href="?p=clientes">Voltar à lista</a></div></div>
