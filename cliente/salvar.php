<?php
session_start();
require_once '../Cliente.php';

$mensagem = '';
$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnsalvar'])) {
    $nome = trim($_POST['txtnome'] ?? '');
    $email = trim($_POST['txtemail'] ?? '');

    if (empty($nome) || empty($email)) {
        $mensagem = "Nome e e-mail são obrigatórios.";
        $erro = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido.";
        $erro = true;
    } else {
        $cliente = new Cliente();
        $cliente->setNome($nome);
        $cliente->setEmail($email);

        if ($cliente->salvar()) {
            $_SESSION['sucesso'] = "Cliente cadastrado com sucesso!";
            header("Location: ?p=clientes");
            exit;
        } else {
            $mensagem = "Erro ao cadastrar cliente.";
            $erro = true;
        }
    }
}
?>

<h3 class="mt-3 text-primary">Novo Cliente</h3>

<div class="card shadow mt-3">
    <form method="post" class="m-3">
        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $erro ? 'danger' : 'success' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <div class="form-group row">
            <label for="txtnome" class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="txtnome" name="txtnome" required 
                       value="<?= htmlspecialchars($_POST['txtnome'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="txtemail" class="col-sm-2 col-form-label">E-mail</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="txtemail" name="txtemail" required 
                       value="<?= htmlspecialchars($_POST['txtemail'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <input type="submit" class="btn btn-primary" name="btnsalvar" value="Cadastrar">
                <a href="?p=clientes" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>