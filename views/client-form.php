<section class="surface form-section">
    <div class="surface-header"><div><h2 class="h6 fw-bold mb-1">Novo cliente</h2><p class="small text-secondary mb-0">O e-mail deve ser único no cadastro.</p></div><a class="btn btn-sm btn-light border" href="?p=clientes"><i class="bi bi-arrow-left me-1"></i> Voltar</a></div>
    <form method="post" action="?p=add/cliente" class="surface-body">
        <?= csrf_field() ?>
        <div class="mb-3"><label for="nome" class="form-label fw-semibold">Nome completo *</label><input class="form-control" id="nome" name="nome" maxlength="100" autocomplete="name" required value="<?= e($oldInput['nome'] ?? '') ?>" placeholder="Ex.: Maria da Silva"></div>
        <div class="mb-4"><label for="email" class="form-label fw-semibold">E-mail *</label><input class="form-control" type="email" id="email" name="email" maxlength="100" autocomplete="email" required value="<?= e($oldInput['email'] ?? '') ?>" placeholder="nome@empresa.com"></div>
        <button class="btn btn-success" type="submit"><i class="bi bi-check2 me-1"></i> Salvar cliente</button> <a href="?p=clientes" class="btn btn-light border">Cancelar</a>
    </form>
</section>
