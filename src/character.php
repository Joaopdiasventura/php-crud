<?php
$conn = pg_connect("host=" . getenv('DB_HOST') .
    " port=" . getenv('DB_PORT') .
    " dbname=" . getenv('DB_NAME') .
    " user=" . getenv('DB_USER') .
    " password=" . getenv('DB_PASSWORD'));

$id = $_GET['id'];

$sql = "SELECT * FROM characters WHERE id = $1";
$result = pg_query_params($conn, $sql, array($id));
$personagem = pg_fetch_assoc($result);

if (!$personagem) {
    header("Location: ../");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $sql = "DELETE FROM characters WHERE id = $1";
        pg_query_params($conn, $sql, array($id));

        $imagem_antiga = 'assets/' . $personagem['image'];
        if (file_exists($imagem_antiga)) {
            unlink($imagem_antiga);
        }

        header("Location: ../");
        exit();
    } else {
        $nome = $_POST['nome'];
        $poderes = $_POST['poderes'];
        $imagem = isset($_FILES['image']['name']) && $_FILES['image']['name'] != '' ? $_FILES['image']['name'] : $personagem['image'];

        if (!is_dir('assets')) {
            mkdir('assets', 0777, true);
        }

        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $imagem_antiga = 'assets/' . $personagem['image'];
            if (file_exists($imagem_antiga)) {
                unlink($imagem_antiga);
            }

            $destino = 'assets/' . basename($imagem);
            move_uploaded_file($_FILES['image']['tmp_name'], $destino);
        }

        $sql = "UPDATE characters SET name = $1, powers = $2, image = $3 WHERE id = $4";
        pg_query_params($conn, $sql, array($nome, $poderes, $imagem, $id));
        header("Location: ../");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../public/image.png">
    <title>JOJO'S BIZZARE ADVENTURE - <?php echo htmlspecialchars($personagem['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-yellow-300">
    <div class="container mx-auto py-10 text-center">

        <form action="character.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md mx-auto mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-yellow-300">Editar Personagem</h2>
            <div class="mb-4">
                <label class="block text-yellow-300 font-semibold mb-1" for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($personagem['name']); ?>" required class="w-full p-3 rounded-lg border border-purple-500 bg-gray-700 text-yellow-200 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <div class="mb-4">
                <label class="block text-yellow-300 font-semibold mb-1" for="nome">Poderes:</label>
                <textarea name="poderes" id="poderes" required class="w-full p-3 rounded-lg border border-purple-500 bg-gray-700 text-yellow-200 focus:outline-none focus:ring-2 focus:ring-purple-600 resize-none"><?php echo htmlspecialchars($personagem['powers']); ?></textarea>
            </div>

            <div class="mb-6 text-center">
                <label class="block text-yellow-300 font-semibold mb-2">Imagem:</label>
                <label for="image">
                    <img id="previewImage" src="assets/<?php echo htmlspecialchars($personagem['image']); ?>" alt="Ícone de Câmera" class="cursor-pointer w-40 h-40 object-cover border-4 border-dashed border-purple-500 rounded-lg mx-auto hover:opacity-80 transition-opacity">
                </label>
                <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewFile()">
            </div>

            <button type="submit" class="w-full bg-purple-700 hover:bg-purple-800 text-white font-bold py-3 rounded-lg transition-transform transform hover:scale-105 shadow-lg">
                Salvar Alterações
            </button>
        </form>

        <form action="character.php?id=<?php echo $id; ?>" method="POST" class="max-w-md mx-auto mb-4">
            <button type="submit" name="delete" value="1" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 rounded-lg transition-transform transform hover:scale-105 shadow-lg">
                Deletar Personagem
            </button>
        </form>

        <a href="../" class="w-full inline-block text-center bg-gray-700 hover:bg-gray-800 text-white font-bold py-3 rounded-lg transition-transform transform hover:scale-105 shadow-lg max-w-md mx-auto">
            Voltar
        </a>
    </div>
    <script src="./scripts/previweFile.js"></script>
</body>

</html>