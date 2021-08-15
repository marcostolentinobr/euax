<?php
require_once __DIR__ . '/../libs/Conexao.php';
$Pdo = new Conexao('EUAX');

//select
$select = '
    SELECT P.id,
           P.nome,
           P.data_inicio,
           P.data_fim,
           P.data_concluido 
      FROM PROJETO P
';

$MODULO = [
    'descricao' => 'Projetos',
    'acao' => 'insert',
    'acao_descricao' => 'Incluir'
];

echo "<h1>$MODULO[descricao]</h1>";
try {

    //insert
    if (isset($_POST['ACAO']) && $_POST['ACAO'] == 'insert') {

        //query
        $insert = '
            INSERT INTO PROJETO 
            (nome ,  data_inicio,  data_fim) VALUES 
            (:nome, :data_inicio, :data_fim)
        ';

        //execute
        $execute = $Pdo->execute($insert, [
            'nome'        => trim($_POST['nome']),
            'data_inicio' => $_POST['data_inicio'],
            'data_fim'    => $_POST['data_fim'],
        ]);

        echo mensagem_acao('insert', $execute['prepare']->rowCount());
    }
    //delete
    elseif (isset($_POST['ACAO']) && $_POST['ACAO'] == 'delete') {

        //query
        $delete = '
            DELETE 
              FROM PROJETO 
             WHERE id = :id
        ';

        //execute
        $execute = $Pdo->execute($delete, [
            'id' => $_POST['id']
        ]);

        //mensagem
        echo mensagem_acao('delete', $execute['prepare']->rowCount());
    }
    //update
    elseif (isset($_POST['ACAO']) && $_POST['ACAO'] == 'update') {

        //query
        $delete = '
            UPDATE PROJETO 
               SET nome        = :nome,
                   data_inicio = :data_inicio,
                   data_fim    = :data_fim
             WHERE id = :id';

        //execute
        $execute = $Pdo->execute($delete, [
            'nome'        => trim($_POST['nome']),
            'data_inicio' => $_POST['data_inicio'],
            'data_fim'    => $_POST['data_fim'],
            'id'    => $_POST['id'],
        ]);

        //mensagem
        echo mensagem_acao('update', $execute['prepare']->rowCount());
    }
    //edit
    elseif (isset($_POST['ACAO']) && $_POST['ACAO'] == 'edit') {
        $MODULO['acao'] = 'update';
        $MODULO['acao_descricao'] = 'Alterar';

        //where
        $select_edit = $select . '
            WHERE P.id = :id
        ';

        //dado
        $Dado = $Pdo->fetchAll(
            $select_edit,
            ['id' => $_POST['id']],
            true
        );
    }
} catch (Exception $e) {
    echo mensagem_acao('erro', $e->getMessage());
}

try {
    $Dados = $Pdo->fetchAll($select);
} catch (Exception $e) {
    $Dados = new stdClass();
    $Dados->msg = mensagem_acao('erro', $e->getMessage());
}

if (!isset($Dado)) {
    $Dado = new stdClass();
}

//Dado para edição
$Dado->id          = isset($Dado->id)          ? $Dado->id          : '';
$Dado->nome        = isset($Dado->nome)        ? $Dado->nome        : '';
$Dado->data_inicio = isset($Dado->data_inicio) ? $Dado->data_inicio : '';
$Dado->data_fim    = isset($Dado->data_fim)    ? $Dado->data_fim    : '';
?>

<!-- formulario -->
<form method="POST">

    <!-- id -->
    <input name="id" value="<?= $Dado->id ?>" hidden>

    <!-- nome -->
    <label for="nome">Nome:</label><br>
    <input name="nome" id="nome" type="text" value="<?= $Dado->nome ?>" required>
    <br><br>

    <!-- data_inicio -->
    <label for="data_inicio">Início:</label><br>
    <input name="data_inicio" id="data_inicio" type="date" value="<?= $Dado->data_inicio ?>" required>
    <br><br>

    <!-- data_fim -->
    <label for="data_fim">Fim:</label><br>
    <input name="data_fim" id="data_fim" type="date" value="<?= $Dado->data_fim ?>" required>
    <br><br>

    <!-- insert -->
    <button name="ACAO" value="<?= $MODULO['acao'] ?>"><?= $MODULO['acao_descricao'] ?></button>
</form>
<!-- fim formulario -->

<BR>

<!-- select -->
<table border="1">
    <thead>

        <!-- cabeçalho -->
        <th>Projeto</th>
        <th>Início</th>
        <th>Fim</th>
        <th>Concluído</th>
        <th>Ações</th>

    </thead>
    <tbody>

        <?php if (isset($Dados->msg)) : ?>
            <tr>
                <td colspan="100%"><?= $Dados->msg ?></td>
            </tr>
        <?php elseif (count((array)$Dados) == 0) : ?>
            <tr>
                <td colspan="100%">Sem dados para listar</td>
            </tr>
        <?php else : ?>
            <?php foreach ($Dados as $dado) : ?>
                <tr>

                    <!-- dados -->
                    <td><?= $dado->nome ?></td>
                    <td><?= $dado->data_inicio ?></td>
                    <td><?= $dado->data_fim ?></td>
                    <td><?= $dado->data_concluido ?></td>

                    <!-- ações -->
                    <td>
                        <form method="POST">
                            <input name="id" value="<?= $dado->id ?>" hidden>
                            <button name="ACAO" value="edit">Editar</button>
                            <button name="ACAO" value="delete">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
    </tbody>
</table>
<!-- fim select -->