<?php
declare(strict_types=1);

function handle_post(string $route, PDO $pdo): array
{
    verify_csrf();

    switch ($route) {
        case 'add/cliente':
            $nome = required_text($_POST, 'nome', 'nome', 100);
            $email = trim((string) ($_POST['email'] ?? ''));
            if (strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Informe um e-mail válido com até 100 caracteres.');
            }
            $stmt = $pdo->prepare('INSERT INTO cliente (nome, email) VALUES (:nome, :email)');
            $stmt->execute(['nome' => $nome, 'email' => $email]);
            return ['redirect' => 'clientes', 'message' => 'Cliente cadastrado com sucesso.'];

        case 'add/fornecedor':
            $nome = required_text($_POST, 'nome', 'nome', 100);
            $cidade = required_text($_POST, 'cidade', 'cidade', 100);
            $stmt = $pdo->prepare('INSERT INTO fornecedor (nome, cidade) VALUES (:nome, :cidade)');
            $stmt->execute(['nome' => $nome, 'cidade' => $cidade]);
            return ['redirect' => 'fornecedores', 'message' => 'Fornecedor cadastrado com sucesso.'];

        case 'add/categoria':
            $nome = required_text($_POST, 'nome', 'nome da categoria', 80);
            $descricao = trim((string) ($_POST['descricao'] ?? ''));
            if (strlen($descricao) > 255) {
                throw new InvalidArgumentException('A descrição deve ter até 255 caracteres.');
            }
            $stmt = $pdo->prepare('INSERT INTO categoria (nome, descricao) VALUES (:nome, :descricao)');
            $stmt->execute(['nome' => $nome, 'descricao' => $descricao !== '' ? $descricao : null]);
            return ['redirect' => 'categorias', 'message' => 'Categoria criada com sucesso.'];

        case 'add/produto':
            $nome = required_text($_POST, 'nome', 'nome do item', 150);
            $sku = trim((string) ($_POST['sku'] ?? ''));
            if (strlen($sku) > 60) {
                throw new InvalidArgumentException('O código do item deve ter até 60 caracteres.');
            }
            $quantidade = positive_integer($_POST, 'quantidade', 'quantidade', true);
            $estoqueMinimo = positive_integer($_POST, 'estoque_minimo', 'estoque mínimo', true);
            $preco = money_value($_POST, 'preco', 'custo unitário');
            $categoriaId = optional_foreign_key($pdo, 'categoria', $_POST['categoria_id'] ?? '');
            $fornecedorId = optional_foreign_key($pdo, 'fornecedor', $_POST['fornecedor_id'] ?? '');

            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO produto (nome, sku, categoria_id, fornecedor_id, quantidade, estoque_minimo, custo) '
                    . 'VALUES (:nome, :sku, :categoria_id, :fornecedor_id, :quantidade, :estoque_minimo, :custo)'
                );
                $stmt->execute([
                    'nome' => $nome,
                    'sku' => $sku !== '' ? $sku : null,
                    'categoria_id' => $categoriaId,
                    'fornecedor_id' => $fornecedorId,
                    'quantidade' => $quantidade,
                    'estoque_minimo' => $estoqueMinimo,
                    'custo' => $preco,
                ]);
                $productId = (int) $pdo->lastInsertId();
                if ($quantidade > 0) {
                    $movement = $pdo->prepare(
                        "INSERT INTO movimentacao_estoque (produto_id, tipo, quantidade, observacao) "
                        . "VALUES (:produto_id, 'entrada', :quantidade, 'Saldo inicial')"
                    );
                    $movement->execute(['produto_id' => $productId, 'quantidade' => $quantidade]);
                }
                $pdo->commit();
            } catch (Throwable $error) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                throw $error;
            }
            return ['redirect' => 'produtos', 'message' => 'Item cadastrado no estoque.'];

        case 'movimentacoes':
            $produtoId = positive_integer($_POST, 'produto_id', 'item');
            $tipo = (string) ($_POST['tipo'] ?? '');
            if (!in_array($tipo, ['entrada', 'saida'], true)) {
                throw new InvalidArgumentException('Escolha entrada ou saída de estoque.');
            }
            $quantidade = positive_integer($_POST, 'quantidade', 'quantidade');
            $observacao = trim((string) ($_POST['observacao'] ?? ''));
            if (strlen($observacao) > 255) {
                throw new InvalidArgumentException('A observação deve ter até 255 caracteres.');
            }

            $pdo->beginTransaction();
            try {
                $lock = $pdo->prepare('SELECT quantidade FROM produto WHERE id = :id FOR UPDATE');
                $lock->execute(['id' => $produtoId]);
                $product = $lock->fetch();
                if (!$product) {
                    throw new InvalidArgumentException('O item selecionado não existe mais.');
                }
                $current = (int) $product['quantidade'];
                if ($tipo === 'saida' && $quantidade > $current) {
                    throw new InvalidArgumentException("Estoque insuficiente. Há {$current} unidade(s) disponível(is).");
                }
                $newQuantity = $tipo === 'entrada' ? $current + $quantidade : $current - $quantidade;
                $update = $pdo->prepare('UPDATE produto SET quantidade = :quantidade WHERE id = :id');
                $update->execute(['quantidade' => $newQuantity, 'id' => $produtoId]);
                $insert = $pdo->prepare(
                    'INSERT INTO movimentacao_estoque (produto_id, tipo, quantidade, observacao) '
                    . 'VALUES (:produto_id, :tipo, :quantidade, :observacao)'
                );
                $insert->execute([
                    'produto_id' => $produtoId,
                    'tipo' => $tipo,
                    'quantidade' => $quantidade,
                    'observacao' => $observacao !== '' ? $observacao : null,
                ]);
                $pdo->commit();
            } catch (Throwable $error) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                throw $error;
            }
            return ['redirect' => 'movimentacoes', 'message' => 'Estoque atualizado e movimentação registrada.'];

        default:
            throw new InvalidArgumentException('Esta ação não está disponível.');
    }
}

function optional_foreign_key(PDO $pdo, string $table, mixed $value): ?int
{
    if ($value === '' || $value === null) {
        return null;
    }
    $id = filter_var($value, FILTER_VALIDATE_INT);
    if ($id === false || $id < 1) {
        throw new InvalidArgumentException('Selecione uma categoria ou fornecedor válido.');
    }
    if (!in_array($table, ['categoria', 'fornecedor'], true)) {
        throw new InvalidArgumentException('Referência inválida.');
    }
    $stmt = $pdo->prepare("SELECT id FROM {$table} WHERE id = :id");
    $stmt->execute(['id' => $id]);
    if (!$stmt->fetchColumn()) {
        throw new InvalidArgumentException('A categoria ou fornecedor selecionado não existe.');
    }
    return (int) $id;
}
