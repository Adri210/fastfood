<?php
// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Lanchonete</title>
    <link rel="stylesheet" href="./styles/home.css">
</head>
<body>
<header>
    <div class="logo">
        <h1>Fast Food</h1>
    </div>
</header>

<!-- Formulário para adicionar ou editar um produto -->
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nome" placeholder="Nome" required/>
    <input type="text" name="preco" placeholder="Preço" required/>
    <input type="file" name="foto" accept="image/*" required/>
    <br/>
    <textarea class="text-area" name="descricao" placeholder="Descrição do Produto" required></textarea>
    <br/>
    <!-- Dropdown para selecionar a categoria do produto -->
    <select name="categoria" required>
        <option value="Bebida">Bebida</option>
        <option value="Lanche">Lanche</option>
        <option value="Pizza">Pizza</option>
    </select>
    <input type="submit" name="acao" value="Salvar">
</form>

<div id="produtos">
    <?php
    // Exibir os produtos cadastrados
    class Estoque {
        public function listarProdutos() {
            global $pdo;  // Acessa a conexão PDO global

            // Consulta os produtos no banco de dados
            $sql = "SELECT * FROM produtos";
            $stmt = $pdo->query($sql);
            
            while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='card'>
                        <img src='{$produto['foto']}' alt='{$produto['nome']}'>
                        <h3>{$produto['nome']}</h3>
                        <p>Preço: R$ {$produto['preco']}</p>
                        <p>Categoria: {$produto['categoria']}</p>
                        <p>Descrição: {$produto['descricao']}</p>
                        <button onclick='abrirModalEditar({$produto['id']}, \"{$produto['nome']}\", \"{$produto['preco']}\", \"{$produto['categoria']}\", \"{$produto['foto']}\", \"{$produto['descricao']}\")'>Editar</button>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='id_remover' value='{$produto['id']}'>
                            <input type='submit' name='acao' value='Excluir'>
                        </form>
                      </div>";
            }
        }

        // Método para obter produto por ID
        public function getProdutoPorId($id) {
            global $pdo;
            $sql = "SELECT * FROM produtos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    // Salvar o produto no banco de dados
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["acao"])) {
        if ($_POST["acao"] == "Salvar") {
            // Verifica se o formulário foi enviado corretamente
            if (isset($_POST["nome"]) && isset($_POST["preco"]) && isset($_FILES["foto"]) && isset($_POST["categoria"]) && isset($_POST["descricao"])) {
                $nome = $_POST["nome"];
                $preco = floatval($_POST["preco"]);
                $categoria = $_POST["categoria"];
                $descricao = $_POST["descricao"];
                $foto = "uploads/" . basename($_FILES["foto"]["name"]);
                
                // Move a imagem para a pasta de uploads
                move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);

                // Inserir produto no banco de dados
                $sql = "INSERT INTO produtos (nome, preco, foto, categoria, descricao) VALUES (:nome, :preco, :foto, :categoria, :descricao)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':preco', $preco);
                $stmt->bindParam(':foto', $foto);
                $stmt->bindParam(':categoria', $categoria);
                $stmt->bindParam(':descricao', $descricao);
                
                $stmt->execute();
            }
        } elseif ($_POST["acao"] == "Excluir") {
            $id = $_POST["id_remover"];

            // Excluir produto do banco de dados
            $sql = "DELETE FROM produtos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            
            $stmt->execute();
        } elseif ($_POST["acao"] == "Atualizar") {
            $id = $_POST["idOriginal"];
            $nome = $_POST["nome"];
            $preco = floatval($_POST["preco"]);
            $categoria = $_POST["categoria"];
            $descricao = $_POST["descricao"];
            $foto = $_FILES["foto"]["name"] ? "uploads/" . basename($_FILES["foto"]["name"]) : null;

            // Se uma nova foto foi carregada, mova-a para a pasta
            if ($foto) {
                move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);
            } else {
                // Caso contrário, mantenha a foto existente
                $estoque = new Estoque();
                $produto = $estoque->getProdutoPorId($id);
                $foto = $produto['foto'];
            }

            // Atualizar produto no banco de dados
            $sql = "UPDATE produtos SET nome = :nome, preco = :preco, categoria = :categoria, descricao = :descricao, foto = :foto WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':foto', $foto);

            $stmt->execute();
        }
    }

    $estoque = new Estoque();
    $estoque->listarProdutos();
    ?>
</div>

<!-- Modal de Edição -->
<div id="modalEditar" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fecharModalEditar()">&times;</span>
        <h2>Editar Produto</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" id="idOriginal" name="idOriginal">
            <input type="text" id="nomeEditar" name="nome" placeholder="Nome" required>
            <input type="text" id="precoEditar" name="preco" placeholder="Preço" required>
            <textarea id="descricaoEditar" name="descricao" placeholder="Descrição do Produto" required></textarea>
            <input type="file" id="fotoEditar" name="foto" accept="image/*">
            <select id="categoriaEditar" name="categoria" required>
                <option value="Bebida">Bebida</option>
                <option value="Lanche">Lanche</option>
                <option value="Pizza">Pizza</option>
            </select>
            <input type="submit" name="acao" value="Atualizar">
        </form>
    </div>
</div>

<script>
    // Função para abrir o modal de edição
    function abrirModalEditar(id, nome, preco, categoria, foto, descricao) {
        document.getElementById('idOriginal').value = id;
        document.getElementById('nomeEditar').value = nome;
        document.getElementById('precoEditar').value = preco;
        document.getElementById('categoriaEditar').value = categoria;
        document.getElementById('descricaoEditar').value = descricao;
        document.getElementById('modalEditar').style.display = 'block';
    }

    // Função para fechar o modal de edição
    function fecharModalEditar() {
        document.getElementById('modalEditar').style.display = 'none';
    }

    // Fechar o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        var modal = document.getElementById('modalEditar');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
</body>
</html>