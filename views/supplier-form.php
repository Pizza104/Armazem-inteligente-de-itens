<section class="surface form-section">
    <div class="surface-header"><div><h2 class="h6 fw-bold mb-1">Novo fornecedor</h2><p class="small text-secondary mb-0">Guarde os dados básicos para vincular ao estoque.</p></div><a class="btn btn-sm btn-light border" href="?p=fornecedores"><i class="bi bi-arrow-left me-1"></i> Voltar</a></div>
    <form method="post" action="?p=add/fornecedor" class="surface-body">
        <?= csrf_field() ?>
        <div class="mb-3"><label for="nome" class="form-label fw-semibold">Nome do fornecedor *</label><input class="form-control" id="nome" name="nome" maxlength="100" required value="<?= e($oldInput['nome'] ?? '') ?>" placeholder="Ex.: Distribuidora Central"></div>
        <div class="mb-4"><label for="cidade" class="form-label fw-semibold">Cidade *</label><input class="form-control" id="cidade" name="cidade" maxlength="100" required value="<?= e($oldInput['cidade'] ?? '') ?>" placeholder="Ex.: São Paulo"></div>
        <button class="btn btn-success" type="submit"><i class="bi bi-check2 me-1"></i> Salvar fornecedor</button> <a href="?p=fornecedores" class="btn btn-light border">Cancelar</a>
    </form>
</section>
