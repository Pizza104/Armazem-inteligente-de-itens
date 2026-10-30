<?php
$productCount = (int) $pdo->query('SELECT COUNT(*) FROM produto')->fetchColumn();
$unitCount = (int) $pdo->query('SELECT COALESCE(SUM(quantidade), 0) FROM produto')->fetchColumn();
$lowCount = (int) $pdo->query('SELECT COUNT(*) FROM produto WHERE quantidade <= estoque_minimo')->fetchColumn();
$clientCount = (int) $pdo->query('SELECT COUNT(*) FROM cliente')->fetchColumn();
$supplierCount = (int) $pdo->query('SELECT COUNT(*) FROM fornecedor')->fetchColumn();
$lowStock = $pdo->query(
    'SELECT p.id, p.nome, p.sku, p.quantidade, p.estoque_minimo, c.nome AS categoria '
    . 'FROM produto p LEFT JOIN categoria c ON c.id = p.categoria_id '
    . 'WHERE p.quantidade <= p.estoque_minimo ORDER BY p.quantidade ASC, p.nome ASC LIMIT 8'
)->fetchAll();
?>
<section class="surface welcome-panel mb-4">
    <div class="row align-items-center g-3">
        <div class="col-lg">
            <div class="eyebrow">Seu estoque, em perspectiva</div>
            <h2 class="h3 fw-bold mt-2 mb-2">Bom ter tudo em ordem.</h2>
            <p class="mb-0">Cadastre seus itens e acompanhe cada entrada e saída com clareza.</p>
        </div>
        <div class="col-lg-auto d-flex flex-wrap gap-2">
            <a class="btn btn-light" href="?p=add/produto"><i class="bi bi-plus-lg me-1"></i> Cadastrar item</a>
            <a class="btn btn-outline-light" href="?p=movimentacoes"><i class="bi bi-arrow-left-right me-1"></i> Registrar movimento</a>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl"><div class="stat-card"><div class="d-flex justify-content-between"><span class="stat-label">Itens cadastrados</span><span class="stat-icon"><i class="bi bi-box-seam"></i></span></div><div class="stat-value"><?= number_format($productCount, 0, ',', '.') ?></div></div></div>
    <div class="col-6 col-xl"><div class="stat-card"><div class="d-flex justify-content-between"><span class="stat-label">Unidades em estoque</span><span class="stat-icon"><i class="bi bi-stack"></i></span></div><div class="stat-value"><?= number_format($unitCount, 0, ',', '.') ?></div></div></div>
    <div class="col-6 col-xl"><div class="stat-card"><div class="d-flex justify-content-between"><span class="stat-label">Precisam de reposição</span><span class="stat-icon" style="background:var(--amber);color:#916317"><i class="bi bi-exclamation-triangle"></i></span></div><div class="stat-value"><?= number_format($lowCount, 0, ',', '.') ?></div></div></div>
    <div class="col-6 col-xl"><div class="stat-card"><div class="d-flex justify-content-between"><span class="stat-label">Fornecedores</span><span class="stat-icon"><i class="bi bi-truck"></i></span></div><div class="stat-value"><?= number_format($supplierCount, 0, ',', '.') ?></div></div></div>
    <div class="col-6 col-xl"><div class="stat-card"><div class="d-flex justify-content-between"><span class="stat-label">Clientes</span><span class="stat-icon"><i class="bi bi-people"></i></span></div><div class="stat-value"><?= number_format($clientCount, 0, ',', '.') ?></div></div></div>
</div>

<section class="surface">
    <div class="surface-header">
        <div><h2 class="h6 fw-bold mb-1">Atenção ao estoque</h2><p class="small text-secondary mb-0">Itens no limite mínimo ou abaixo dele.</p></div>
        <a class="btn btn-sm btn-outline-success" href="?p=produtos">Ver estoque <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <?php if (!$lowStock): ?>
        <div class="empty-state"><i class="bi bi-check2-circle d-block mb-2"></i><div class="fw-semibold text-dark">Tudo certo por aqui</div><div class="small">Nenhum item precisa de reposição no momento.</div></div>
    <?php else: ?>
        <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Item</th><th>Categoria</th><th>Disponível</th><th>Mínimo</th><th>Status</th></tr></thead><tbody>
            <?php foreach ($lowStock as $item): ?>
                <tr class="low-stock-row"><td class="fw-semibold"><?= e($item['nome']) ?><?php if ($item['sku']): ?><div class="small text-secondary"><?= e($item['sku']) ?></div><?php endif; ?></td><td><?= e($item['categoria'] ?: 'Sem categoria') ?></td><td><?= number_format((int) $item['quantidade'], 0, ',', '.') ?></td><td><?= number_format((int) $item['estoque_minimo'], 0, ',', '.') ?></td><td><span class="badge <?= (int) $item['quantidade'] === 0 ? 'badge-out' : 'badge-low' ?>"><?= (int) $item['quantidade'] === 0 ? 'Sem estoque' : 'Repor em breve' ?></span></td></tr>
            <?php endforeach; ?>
        </tbody></table></div>
    <?php endif; ?>
</section>
