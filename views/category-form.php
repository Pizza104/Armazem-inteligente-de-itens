<section class="surface form-section">
    <div class="surface-header"><div><h2 class="h6 fw-bold mb-1">Nova categoria</h2><p class="small text-secondary mb-0">Use nomes curtos para facilitar a filtragem do estoque.</p></div><a class="btn btn-sm btn-light border" href="?p=categorias"><i class="bi bi-arrow-left me-1"></i> Voltar</a></div>
    <form method="post" action="?p=add/categoria" class="surface-body">
        <?= csrf_field() ?>
        <div class="mb-3"><label for="nome" class="form-label fw-semibold">Nome *</label><input class="form-control" id="nome" name="nome" maxlength="80" required value="<?= e($oldInput['nome'] ?? '') ?>" placeholder="Ex.: Material de escritório"></div>
        <div class="mb-4"><label for="descricao" class="form-label fw-semibold">Descrição</label><textarea class="form-control" id="descricao" name="descricao" maxlength="255" rows="3" placeholder="Uma breve descrição (opcional)"><?= e($oldInput['descricao'] ?? '') ?></textarea></div>
        <button class="btn btn-success" type="submit"><i class="bi bi-check2 me-1"></i> Salvar categoria</button> <a href="?p=categorias" class="btn btn-light border">Cancelar</a>
    </form>
</section>
