<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastBite</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
    <header>
        <div class="logo">
            <h1>Fast Food</h1>
        </div>
        <div class="carrinho">
        <i class="fa-solid fa-cart-shopping" style="color: #ffffff;"></i>
        </div>
    </header>

    <main>
      
        <div class="filtro">
            <button onclick="filtrarItens('lanche')">Lanche</button>
            <button onclick="filtrarItens('pizza')">Pizza</button>
            <button onclick="filtrarItens('bebida')">Bebida</button>
        </div>

        <div class="menu">
    <div class="lanche tipo-lanche" onclick="mostrarDetalhes('xsalada')">
        <img src="imagens/HBG.jpeg" alt="X Salada">  
        <p>X Salada</p>
    </div>
    <div class="lanche tipo-pizza" onclick="mostrarDetalhes('pizzacalabresa')">
        <img src="imagens/pizza.jpeg" alt="Pizza de Calabresa"> 
    </div>
    <div class="lanche tipo-bebida" onclick="mostrarDetalhes('coca')">
        <img src="imagens/coca.jpeg" alt="Coca-Cola">
        <p>Coca-Cola</p>
    </div>
</div>



        <div id="detalhesLanche" class="detalhesLanche">
            <div id="detalhe" class="card">
                <img src="" alt="Imagem do Lanche" id="imagemLanche">
                <p id="descricaoLanche"></p>
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" min="1" value="1">
                <button onclick="adicionarCarrinho()">Adicionar ao Carrinho</button>
            </div>
        </div>
    </main>

   
    <footer>
        <p>&copy; 2025 Fast Food</p>
    </footer>

   
</body>
</html>
