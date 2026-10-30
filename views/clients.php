<?php
$clients = $pdo->query('SELECT id, nome, email FROM cliente ORDER BY nome')->fetchAll();
?>
<section class="surface">
    <div class="surface-header flex-wrap"><div><h2 class="h6 fw-bold mb-1">Clientes</h2><p class="small text-secondary mb-0"><?= count($clients) ?> cadastro(s)</p></div><div class="d-flex gap-2"><input class="form-control search-box" data-table-search="#client-rows" placeholder="Buscar cliente" aria-label="Buscar cliente"><a class="btn btn-success text-nowrap" href="?p=add/cliente"><i class="bi bi-plus-lg me-1"></i> Novo</a></div></div>
    <?php if (!$clients): ?><div class="empty-state"><i class="bi bi-people d-block mb-2"></i><div class="fw-semibold text-dark">Nenhum cliente cadastrado</div><div class="small mb-3">Adicione clientes para manter seus cadastros organizados.</div><a class="btn btn-sm btn-success" href="?p=add/cliente">Cadastrar cliente</a></div><?php else: ?>
        <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Nome</th><th>E-mail</th><th>ID</th></tr></thead><tbody id="client-rows"><?php foreach ($clients as $client): ?><tr data-search-row><td class="fw-semibold"><i class="bi bi-person-circle text-success me-2"></i><?= e($client['nome']) ?></td><td><a class="text-decoration-none" href="mailto:<?= e($client['email']) ?>"><?= e($client['email']) ?></a></td><td class="text-secondary">#<?= (int) $client['id'] ?></td></tr><?php endforeach; ?></tbody></table></div>
    <?php endif; ?>
</section>
