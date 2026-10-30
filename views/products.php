<?php
$query = trim((string) ($_GET['q'] ?? ''));
$sql = 'SELECT p.id, p.nome, p.sku, p.quantidade, p.estoque_minimo, p.custo, c.nome AS categoria, f.nome AS fornecedor '
    . 'FROM produto p LEFT JOIN categoria c ON c.id = p.categoria_id '
    . 'LEFT JOIN fornecedor f ON f.id = p.fornecedor_id';
$params = [];
if ($query !== '') {
    $sql .= ' WHERE p.nome LIKE :q OR p.sku LIKE :q OR c.nome LIKE :q OR f.nome LIKE :q';
    $params['q'] = '%' . $query . '%';
}
$sql .= ' ORDER BY p.nome ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<section class="surface">
    <div class="surface-header flex-wrap">
        <div><h2 class="h6 fw-bold mb-1">Todos os itens</h2><p class="small text-secondary mb-0"><?= count($products) ?> resultado(s)</p></div>
        <div class="d-flex gap-2 flex-wrap">
            <form method="get" class="search-box"><input type="hidden" name="p" value="produtos"><label class="visually-hidden" for="search-products">Buscar estoque</label><div class="input-group"><span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-secondary"></i></span><input id="search-products" class="form-control border-start-0" name="q" value="<?= e($query) ?>" placeholder="Buscar item, código ou categoria"></div></form>
            <a href="?p=add/produto" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i> Novo item</a>
        </div>
    </div>
    <?php if (!$products): ?>
        <div class="empty-state"><i class="bi bi-box d-block mb-2"></i><div class="fw-semibold text-dark"><?= $query !== '' ? 'Nenhum resultado encontrado' : 'Seu estoque começa aqui' ?></div><div class="small mb-3"><?= $query !== '' ? 'Tente outro termo de busca.' : 'Cadastre o primeiro item para acompanhar quantidades e reposição.' ?></div><?php if ($query === ''): ?><a class="btn btn-sm btn-success" href="?p=add/produto">Cadastrar primeiro item</a><?php endif; ?></div>
    <?php else: ?>
        <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Item</th><th>Categoria</th><th>Fornecedor</th><th>Quantidade</th><th>Custo unitário</th><th>Status</th><th></th></tr></thead><tbody>
            <?php foreach ($products as $product): $quantity = (int) $product['quantidade']; $minimum = (int) $product['estoque_minimo']; ?>
                <tr>
                    <td class="fw-semibold"><?= e($product['nome']) ?><?php if ($product['sku']): ?><div class="small text-secondary">Cód. <?= e($product['sku']) ?></div><?php endif; ?></td>
                    <td><?= e($product['categoria'] ?: '—') ?></td><td><?= e($product['fornecedor'] ?: '—') ?></td>
                    <td><strong><?= number_format($quantity, 0, ',', '.') ?></strong><div class="small text-secondary">mín. <?= number_format($minimum, 0, ',', '.') ?></div></td>
                    <td>R$ <?= number_format((float) $product['custo'], 2, ',', '.') ?></td>
                    <td><?php if ($quantity === 0): ?><span class="badge badge-out">Sem estoque</span><?php elseif ($quantity <= $minimum): ?><span class="badge badge-low">Estoque baixo</span><?php else: ?><span class="badge badge-soft">Disponível</span><?php endif; ?></td>
                    <td><a class="btn btn-sm btn-light border" href="?p=movimentacoes" title="Registrar movimentação"><i class="bi bi-arrow-left-right"></i><span class="visually-hidden">Registrar movimentação</span></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody></table></div>
    <?php endif; ?>
</section>
