<?php
$categories = $pdo->query('SELECT id, nome FROM categoria ORDER BY nome')->fetchAll();
$suppliers = $pdo->query('SELECT id, nome FROM fornecedor ORDER BY nome')->fetchAll();
?>
<section class="surface form-section">
    <div class="surface-header"><div><h2 class="h6 fw-bold mb-1">Detalhes do item</h2><p class="small text-secondary mb-0">Os campos com * são obrigatórios.</p></div><a class="btn btn-sm btn-light border" href="?p=produtos"><i class="bi bi-arrow-left me-1"></i> Voltar</a></div>
    <form method="post" action="?p=add/produto" class="surface-body">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-8"><label for="nome" class="form-label fw-semibold">Nome do item *</label><input class="form-control" id="nome" name="nome" maxlength="150" required value="<?= e($oldInput['nome'] ?? '') ?>" placeholder="Ex.: Caixa organizadora média"></div>
            <div class="col-md-4"><label for="sku" class="form-label fw-semibold">Código / SKU</label><input class="form-control" id="sku" name="sku" maxlength="60" value="<?= e($oldInput['sku'] ?? '') ?>" placeholder="Ex.: CX-001"></div>
            <div class="col-md-6"><label for="categoria_id" class="form-label fw-semibold">Categoria</label><select class="form-select" id="categoria_id" name="categoria_id"><option value="">Sem categoria</option><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= (string) ($oldInput['categoria_id'] ?? '') === (string) $category['id'] ? 'selected' : '' ?>><?= e($category['nome']) ?></option><?php endforeach; ?></select><?php if (!$categories): ?><div class="form-text">Ainda não há categorias. <a href="?p=add/categoria">Criar categoria</a></div><?php endif; ?></div>
            <div class="col-md-6"><label for="fornecedor_id" class="form-label fw-semibold">Fornecedor</label><select class="form-select" id="fornecedor_id" name="fornecedor_id"><option value="">Sem fornecedor</option><?php foreach ($suppliers as $supplier): ?><option value="<?= (int) $supplier['id'] ?>" <?= (string) ($oldInput['fornecedor_id'] ?? '') === (string) $supplier['id'] ? 'selected' : '' ?>><?= e($supplier['nome']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-4"><label for="quantidade" class="form-label fw-semibold">Quantidade inicial *</label><input class="form-control" type="number" id="quantidade" name="quantidade" min="0" step="1" required value="<?= e($oldInput['quantidade'] ?? '0') ?>"></div>
            <div class="col-md-4"><label for="estoque_minimo" class="form-label fw-semibold">Avisar quando chegar a *</label><input class="form-control" type="number" id="estoque_minimo" name="estoque_minimo" min="0" step="1" required value="<?= e($oldInput['estoque_minimo'] ?? '5') ?>"><div class="form-text">Quantidade mínima para o alerta de reposição.</div></div>
            <div class="col-md-4"><label for="preco" class="form-label fw-semibold">Custo unitário (R$)</label><input class="form-control" type="number" id="preco" name="preco" min="0" max="99999999.99" step="0.01" value="<?= e($oldInput['preco'] ?? '0.00') ?>"></div>
            <div class="col-12 d-flex gap-2 pt-2"><button type="submit" class="btn btn-success"><i class="bi bi-check2 me-1"></i> Salvar item</button><a href="?p=produtos" class="btn btn-light border">Cancelar</a></div>
        </div>
    </form>
</section>
