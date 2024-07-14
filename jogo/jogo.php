<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jogo de Adivinhação</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    #game {
      background-color: #fff;
      padding: 20px;
      margin-top: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">Jogo de Adivinhação</h1>
    <div id="game">
      <?php
      // Lógica do Jogo em PHP e JavaScript

      session_start();

      // Inicialização do jogo
      if (!isset($_SESSION['number'])) {
        $_SESSION['number'] = rand(1, 100);
        $_SESSION['attempts'] = 2; // sempre será +1
        echo '<p>Eu escolhi um número entre 1 e 100. Você tem 3 tentativas para adivinhar.</p>';
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recebe o palpite do usuário
        $guess = $_POST['guess'];
        $number = $_SESSION['number'];
        $attempts = $_SESSION['attempts'];

        if ($guess < $number) {
            $_SESSION['attempts']--;
          echo "<p>Seu palpite $guess é menor. Você ainda tem $attempts tentativas.</p>";
        } elseif ($guess > $number) {
          echo "<p>Seu palpite $guess é maior. Você ainda tem $attempts tentativas.</p>";
        } else {
          echo "<p>Parabéns! Você acertou o número $number.</p>";
          echo '<p><a href="?restart=true">Jogar Novamente</a></p>';
          echo '<p><a href="gerar.php">Gesgatar uma key</a></p>';
          session_destroy();
          exit;
unset($_SESSION['chave_gerada']);

        }

        

        if ($_SESSION['attempts'] === -1) {
          echo "<p>Game Over! O número era $number.</p>";
          echo '<p><a href="?restart=true">Jogar Novamente</a></p>';
          session_destroy();
          exit;
        }
      }
      ?>
      
      <form method="post">
        <div class="form-group">
          <label for="guessInput">Digite seu palpite:</label>
          <input type="number" class="form-control" id="guessInput" name="guess" min="1" max="100" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Palpite</button>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
