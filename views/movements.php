<?php
$products = $pdo->query('SELECT id, nome, sku, quantidade FROM produto ORDER BY nome')->fetchAll();
$movements = $pdo->query(
    'SELECT m.id, m.tipo, m.quantidade, m.observacao, m.criado_em, p.nome AS produto, p.sku '
    . 'FROM movimentacao_estoque m INNER JOIN produto p ON p.id = m.produto_id '
    . 'ORDER BY m.criado_em DESC, m.id DESC LIMIT 40'
)->fetchAll();
?>
<div class="row g-4 align-items-start">
    <div class="col-xl-4">
        <section class="surface">
            <div class="surface-header"><div><h2 class="h6 fw-bold mb-1">Registrar movimentação</h2><p class="small text-secondary mb-0">Atualiza o saldo na hora.</p></div></div>
            <?php if (!$products): ?><div class="surface-body"><div class="alert alert-info mb-0">Cadastre um item antes de movimentar o estoque. <a href="?p=add/produto">Adicionar item</a></div></div><?php else: ?>
            <form method="post" action="?p=movimentacoes" class="surface-body">
                <?= csrf_field() ?>
                <div class="mb-3"><label for="produto_id" class="form-label fw-semibold">Item *</label><select class="form-select" id="produto_id" name="produto_id" required><option value="">Escolha um item</option><?php foreach ($products as $product): ?><option value="<?= (int) $product['id'] ?>" <?= (string) ($oldInput['produto_id'] ?? '') === (string) $product['id'] ? 'selected' : '' ?>><?= e($product['nome']) ?> · saldo <?= (int) $product['quantidade'] ?></option><?php endforeach; ?></select></div>
                <div class="mb-3"><label for="tipo" class="form-label fw-semibold">Tipo *</label><select class="form-select" id="tipo" name="tipo" required><option value="entrada" <?= ($oldInput['tipo'] ?? '') === 'entrada' ? 'selected' : '' ?>>Entrada — adicionar unidades</option><option value="saida" <?= ($oldInput['tipo'] ?? '') === 'saida' ? 'selected' : '' ?>>Saída — retirar unidades</option></select></div>
                <div class="mb-3"><label for="quantidade" class="form-label fw-semibold">Quantidade *</label><input class="form-control" type="number" id="quantidade" name="quantidade" min="1" step="1" required value="<?= e($oldInput['quantidade'] ?? '1') ?>"></div>
                <div class="mb-3"><label for="observacao" class="form-label fw-semibold">Observação</label><input class="form-control" id="observacao" name="observacao" maxlength="255" value="<?= e($oldInput['observacao'] ?? '') ?>" placeholder="Ex.: reposição do pedido 123"></div>
                <button class="btn btn-success w-100" type="submit"><i class="bi bi-arrow-left-right me-1"></i> Registrar</button>
            </form><?php endif; ?>
        </section>
    </div>
    <div class="col-xl-8">
        <section class="surface">
            <div class="surface-header"><div><h2 class="h6 fw-bold mb-1">Histórico recente</h2><p class="small text-secondary mb-0">Últimas <?= count($movements) ?> movimentações.</p></div></div>
            <?php if (!$movements): ?><div class="empty-state"><i class="bi bi-arrow-left-right d-block mb-2"></i><div class="fw-semibold text-dark">Nenhuma movimentação ainda</div><div class="small">Entradas e saídas registradas aparecerão aqui.</div></div><?php else: ?>
            <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Data</th><th>Item</th><th>Tipo</th><th>Quantidade</th><th>Observação</th></tr></thead><tbody><?php foreach ($movements as $movement): ?><tr><td class="text-nowrap"><?= e(date('d/m/Y H:i', strtotime($movement['criado_em']))) ?></td><td class="fw-semibold"><?= e($movement['produto']) ?><?php if ($movement['sku']): ?><div class="small text-secondary"><?= e($movement['sku']) ?></div><?php endif; ?></td><td><?php if ($movement['tipo'] === 'entrada'): ?><span class="badge badge-soft"><i class="bi bi-arrow-down-left me-1"></i>Entrada</span><?php else: ?><span class="badge badge-low"><i class="bi bi-arrow-up-right me-1"></i>Saída</span><?php endif; ?></td><td class="fw-semibold"><?= number_format((int) $movement['quantidade'], 0, ',', '.') ?></td><td class="text-secondary"><?= e($movement['observacao'] ?: '—') ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
        </section>
    </div>
</div>
