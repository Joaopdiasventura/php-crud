<?php
$conn = pg_connect("host=" . getenv('DB_HOST') .
    " port=" . getenv('DB_PORT') .
    " dbname=" . getenv('DB_NAME') .
    " user=" . getenv('DB_USER') .
    " password=" . getenv('DB_PASSWORD'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $powers = $_POST['powers'];

    $image = $_FILES['image']['name'];
    $destino = 'assets/' . basename($image);

    if (!is_dir('assets')) {
        mkdir('assets', 0777, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $destino)) {
        $sql = "INSERT INTO characters (name, powers, image) VALUES ($1, $2, $3)";
        $result = pg_query_params($conn, $sql, array($name, $powers, $image));

        if ($result) {
            header("Location: ../");
            exit();
        } else {
            echo "Erro ao inserir no banco de dados.";
        }
    } else {
        echo "Erro ao mover o arquivo de imagem. Verifique se a pasta 'assets' existe e tem permissões de gravação.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../public/image.png">
    <title>JOJO'S BIZZARE ADVENTURE</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-yellow-300 overflow-hidden">
    <div class="container mx-auto py-12 flex flex-col items-center">
        <h1 class="text-4xl font-bold mb-8 tracking-wider border-b-4 border-yellow-300 inline-block pb-2">Adicionar Novo Personagem</h1>

        <form action="create.php" method="POST" enctype="multipart/form-data" class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-lg flex flex-col gap-1.5">
            <div class="mb-6">
                <label class="block text-yellow-300 font-semibold mb-2" for="name">Nome:</label>
                <input type="text" name="name" id="name" required class="w-full p-3 rounded-lg border border-purple-500 bg-gray-700 text-yellow-200 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <div class="mb-6">
                <label class="block text-yellow-300 font-semibold mb-2" for="powers">Poderes:</label>
                <textarea name="powers" id="powers" required class="w-full p-3 rounded-lg border border-purple-500 bg-gray-700 text-yellow-200 focus:outline-none focus:ring-2 focus:ring-purple-600 resize-none"></textarea>
            </div>

            <div class="mb-6 text-center">
                <label class="block text-yellow-300 font-semibold mb-2">Imagem:</label>
                <label for="image">
                    <img id="previewImage" src="https://icones.pro/wp-content/uploads/2021/02/icono-de-camara-gris.png" alt="Ícone de Câmera" class="cursor-pointer w-40 h-40 object-cover border-4 border-dashed border-purple-500 rounded-lg mx-auto hover:opacity-80 transition-opacity">
                </label>
                <input type="file" name="image" id="image" accept="image/*" required class="hidden" onchange="previewFile()">
            </div>

            <button type="submit" class="w-full bg-purple-700 hover:bg-purple-800 text-white font-bold py-3 rounded-lg transition-transform transform hover:scale-105 shadow-lg">
                CRIAR PERSONAGEM
            </button>

            <a href="../" class="w-full text-center bg-gray-700 hover:bg-purple-800 text-white font-bold py-3 rounded-lg transition-transform transform hover:scale-105 shadow-lg mt-4">
                VOLTAR
            </a>

        </form>
    </div>
    <script src="./scripts/previweFile.js"></script>
</body>

</html>