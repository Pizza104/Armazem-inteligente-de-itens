<?php
$categories = $pdo->query(
    'SELECT c.id, c.nome, c.descricao, c.criado_em, COUNT(p.id) AS total_itens '
    . 'FROM categoria c LEFT JOIN produto p ON p.categoria_id = c.id '
    . 'GROUP BY c.id, c.nome, c.descricao, c.criado_em ORDER BY c.nome'
)->fetchAll();
?>
<section class="surface">
    <div class="surface-header flex-wrap"><div><h2 class="h6 fw-bold mb-1">Categorias de itens</h2><p class="small text-secondary mb-0">Organize o catálogo por tipo de produto.</p></div><a class="btn btn-success" href="?p=add/categoria"><i class="bi bi-plus-lg me-1"></i> Nova categoria</a></div>
    <?php if (!$categories): ?><div class="empty-state"><i class="bi bi-tags d-block mb-2"></i><div class="fw-semibold text-dark">Nenhuma categoria cadastrada</div><div class="small mb-3">Crie categorias como “Material”, “Equipamentos” ou “Embalagens”.</div><a class="btn btn-sm btn-success" href="?p=add/categoria">Criar categoria</a></div><?php else: ?>
        <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Categoria</th><th>Descrição</th><th>Itens vinculados</th></tr></thead><tbody><?php foreach ($categories as $category): ?><tr><td class="fw-semibold"><i class="bi bi-tag text-success me-2"></i><?= e($category['nome']) ?></td><td class="text-secondary"><?= e($category['descricao'] ?: '—') ?></td><td><span class="badge badge-soft"><?= number_format((int) $category['total_itens'], 0, ',', '.') ?> item(ns)</span></td></tr><?php endforeach; ?></tbody></table></div>
    <?php endif; ?>
</section>
