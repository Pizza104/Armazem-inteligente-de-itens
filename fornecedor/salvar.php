<?php
session_start();
require_once '../Fornecedor.php';

$mensagem = '';
$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsalvar'])) {
    $nome = trim($_POST['txtnome'] ?? '');
    $cidade = trim($_POST['txtcidade'] ?? '');

    if (empty($nome) || empty($cidade)) {
        $mensagem = "Preencha todos os campos obrigatórios.";
        $erro = true;
    } else {
        $fornecedor = new Fornecedor();
        $fornecedor->setNome($nome);
        $fornecedor->setCidade($cidade);

        if ($fornecedor->salvar()) {
            $_SESSION['sucesso'] = "Fornecedor cadastrado com sucesso!";
            header("Location: ?p=fornecedores");
            exit;
        } else {
            $mensagem = "Erro ao cadastrar fornecedor. Tente novamente.";
            $erro = true;
        }
    }
}
?>

<h3 class="mt-3 text-primary">Fornecedor</h3>

<div class="card shadow mt-3">
    <form method="post" name="formsalvar" id="formSalvar" class="m-3">
        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $erro ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensagem) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="form-group row">
            <label for="txtnome" class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="txtnome" name="txtnome" placeholder="Nome do fornecedor" value="<?= htmlspecialchars($_POST['txtnome'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="txtcidade" class="col-sm-2 col-form-label">Cidade</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="txtcidade" name="txtcidade" placeholder="Cidade do fornecedor" value="<?= htmlspecialchars($_POST['txtcidade'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <input type="submit" class="btn btn-primary" name="btnsalvar" value="Cadastrar">
                <a href="?p=fornecedores" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>