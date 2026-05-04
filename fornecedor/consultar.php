<?php
session_start();
require_once '../Conn.php';

$conn = new Conn();
$pdo = $conn->conectar();

$sql = "SELECT id, nome, cidade FROM fornecedor ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="col-sm-12 mb-4">
    <div class="card shadow mb-4">
        <div class="table-responsive-sm mt-4">
            <h3 class="ml-3">
                Listar Fornecedores
                <a class="btn btn-success float-right mb-3 mr-3" href="?p=add/fornecedor">
                    <i class="bi bi-database-fill-add"></i> Novo
                </a>
            </h3>

            <?php if (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success alert-dismissible fade show ml-3 mr-3" role="alert">
                    <?= htmlspecialchars($_SESSION['sucesso']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php endif; ?>

            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($fornecedores) > 0): ?>
                        <?php foreach ($fornecedores as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['id']) ?></td>
                                <td><?= htmlspecialchars($f['nome']) ?></td>
                                <td><?= htmlspecialchars($f['cidade']) ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning disabled"><i class="bi bi-pencil"></i></a>
                                    <a href="#" class="btn btn-sm btn-danger disabled"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Nenhum fornecedor cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>