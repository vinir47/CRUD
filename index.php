<?php

/**
 * Inclui o arquivo de conexão com o banco de dados.
 *
 * __DIR__ retorna o diretório atual do arquivo,
 * o que evita problemas de caminho relativo.
 */
require __DIR__ . "/connect.php";

/**
 * Obtém a instância da conexão com o banco.
 * Esse método foi definido na classe Connect.
 */
$pdo = Connect::getInstance();

/**
 * Executa uma consulta SQL para buscar todos os usuários
 * da tabela "users", ordenando pelo campo "id" em ordem crescente.
 *
 * query() é usado quando não há parâmetros dinâmicos.
 */
$stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");

/**
 * fetchAll() busca todos os registros retornados pela consulta
 * e os armazena em um array.
 */
$users = $stmt->fetchAll();

$error = filter_input(INPUT_GET, 'error');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD PHP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container"></div>

        <h1>Cadastro de Alunos</h1>

        <?php if ($error): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px; font-family: sans-serif;">
                <?php
                    if ($error === 'email_exists') echo "<strong>Erro:</strong> Este e-mail já está cadastrado.";
                    elseif ($error === 'doc_exists') echo "<strong>Erro:</strong> Este curso/documento já está em uso.";
                    else echo "<strong>Erro:</strong> Ocorreu um problema ao cadastrar.";
                ?>
            </div>
        <?php endif; ?>

        <!--
            Formulário responsável por enviar os dados
            para o arquivo store.php, que fará o cadastro no banco.
            
            method="post" é usado para envio de dados de formulário
            de forma mais apropriada e segura do que GET.
        -->
        <form action="store.php" method="post">
            <p>
                <label>Nome:</label><br>
                <input type="text" name="nomecompleto" required>
            </p>

            <p>
                <label>E-mail:</label><br>
                <input type="email" name="email" required>
            </p>

            <p>
                <label>Curso:</label><br>
                <input type="text" name="document" required>
            </p>

            <button type="submit">Cadastrar</button>
        </form>

        <hr>

        <h2>Lista de alunos</h2>

        <!--
            Tabela que exibe os alunos cadastrados no banco de dados.
            O atributo cellpadding adiciona espaçamento interno nas células.
        -->
        <table cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Curso</th>
                    <th>Cadastrado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!--
                    foreach percorre todos os usuários retornados do banco.
                    A cada repetição, a variável $user representa um aluno.
                -->
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= $user["id"] ?></td>
                        <td><?= $user["name"] ?></td>
                        <td><?= $user["email"] ?></td>
                        <td><?= $user["document"] ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($user["created_at"])) ?></td>
                        <td>
                            <!--
                                Link para editar o aluno.
                                O ID é enviado pela URL para que o arquivo edit.php
                                saiba qual registro deve ser alterado.
                            -->
                            <a href="edit.php?id=<?= $user["id"] ?>">Editar</a> |

                            <!--
                                Link para excluir o aluno.
                                O onclick chama uma confirmação em JavaScript
                                antes de seguir para a exclusão.
                            -->
                            <a href="delete.php?id=<?= $user["id"] ?>" onclick="return confirm('Tem certeza que deseja excluir este aluno?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <!--
                        colspan="6" faz a célula ocupar as 6 colunas da tabela.
                        count($users) conta quantos alunos existem no array.
                    -->
                    <td colspan="6">Total de alunos: <?= count($users) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>