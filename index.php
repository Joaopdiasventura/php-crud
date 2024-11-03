<?php
$conn = pg_connect("host=" . getenv('DB_HOST') .
    " port=" . getenv('DB_PORT') .
    " dbname=" . getenv('DB_NAME') .
    " user=" . getenv('DB_USER') .
    " password=" . getenv('DB_PASSWORD'));

$sql = "SELECT * FROM characters ORDER BY name;";
$result = pg_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="public/image.png">
    <title>JOJO'S BIZZARE ADVENTURE</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-yellow-300">
    <div class="container mx-auto py-10 text-center">
        <h1 class="text-4xl font-bold mb-8 tracking-wider border-b-4 border-yellow-300 inline-block pb-2">Personagens de JOJO'S BIZZARE ADVENTURE</h1>
        <br />

        <a href="src/create.php">
            <button class="bg-purple-700 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded-full mt-4 mb-8 transition-transform transform hover:scale-105 shadow-lg">
                Adicionar Personagem
            </button>
        </a>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($row = pg_fetch_assoc($result)) { ?>
                <a href="src/character.php?id=<?php echo $row['id']; ?>" class="group transform hover:scale-105 transition-transform duration-300">
                    <div class="relative border-4 border-yellow-400 rounded-lg overflow-hidden shadow-lg">
                        <img src="src/assets/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-75 transition-opacity duration-300 flex items-center justify-center">
                            <p class="text-white font-bold text-lg"><?php echo $row['name']; ?></p>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
</body>

</html>