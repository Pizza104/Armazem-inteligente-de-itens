<?php
$suppliers = $pdo->query(
    'SELECT f.id, f.nome, f.cidade, COUNT(p.id) AS total_itens '
    . 'FROM fornecedor f LEFT JOIN produto p ON p.fornecedor_id = f.id '
    . 'GROUP BY f.id, f.nome, f.cidade ORDER BY f.nome'
)->fetchAll();
?>
<section class="surface">
    <div class="surface-header flex-wrap"><div><h2 class="h6 fw-bold mb-1">Fornecedores</h2><p class="small text-secondary mb-0"><?= count($suppliers) ?> cadastro(s)</p></div><div class="d-flex gap-2"><input class="form-control search-box" data-table-search="#supplier-rows" placeholder="Buscar fornecedor" aria-label="Buscar fornecedor"><a class="btn btn-success text-nowrap" href="?p=add/fornecedor"><i class="bi bi-plus-lg me-1"></i> Novo</a></div></div>
    <?php if (!$suppliers): ?><div class="empty-state"><i class="bi bi-truck d-block mb-2"></i><div class="fw-semibold text-dark">Nenhum fornecedor cadastrado</div><div class="small mb-3">Cadastre fornecedores para associá-los aos itens.</div><a class="btn btn-sm btn-success" href="?p=add/fornecedor">Cadastrar fornecedor</a></div><?php else: ?>
        <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Fornecedor</th><th>Cidade</th><th>Itens vinculados</th></tr></thead><tbody id="supplier-rows"><?php foreach ($suppliers as $supplier): ?><tr data-search-row><td class="fw-semibold"><i class="bi bi-truck text-success me-2"></i><?= e($supplier['nome']) ?></td><td><?= e($supplier['cidade']) ?></td><td><?= number_format((int) $supplier['total_itens'], 0, ',', '.') ?></td></tr><?php endforeach; ?></tbody></table></div>
    <?php endif; ?>
</section>
