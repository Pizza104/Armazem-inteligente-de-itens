<?php
session_start();
require_once '../Cliente.php';  // CORRETO

$clientes = Cliente::listarTodos();
?>

<div class="col-sm-12 mb-4">
    <div class="card shadow mb-4">
        <div class="table-responsive-sm mt-4">
            <h3 class="ml-3">
                Listar Clientes
                <a class="btn btn-success float-right mb-3 mr-3" href="?p=add/cliente">
                    <i class="bi bi-database-fill-add"></i> Novo
                </a>
            </h3>

            <?php if (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr><th>ID</th><th>Nome</th><th>E-mail</th><th>Ações</th</tr>
                </thead>
                <tbody>
                    <?php if (count($clientes) > 0): ?>
                        <?php foreach ($clientes as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['id']) ?></td>
                                <td><?= htmlspecialchars($c['nome']) ?></td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning disabled">✏️</a>
                                    <a href="#" class="btn btn-sm btn-danger disabled">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">Nenhum cliente cadastrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>