<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 4 && $_SESSION['role'] != 1)) {
    header('Location: index.php');
    exit;
}
$owner_id = $_SESSION['user_id'];

// Create
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    // Photo pending

    $stmt = $pdo->prepare("INSERT INTO pets (name, species, breed, age, weight, owner_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $species, $breed, $age, $weight, $owner_id]);
}

// Read
$stmt = $pdo->prepare("SELECT * FROM pets WHERE owner_id = ?");
$stmt->execute([$owner_id]);
$pets = $stmt->fetchAll();

// Update (similar, add form)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    // Similar to add, with UPDATE query
    // ...
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ? AND owner_id = ?");
    $stmt->execute([$id, $owner_id]);
    header('Location: pets.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestionar Mascotas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Mascotas</h1>
        <form method="POST">
            <input type="hidden" name="add" value="1">
            <input type="text" name="name" placeholder="Nombre" required>
            <input type="text" name="species" placeholder="Especie" required>
            <input type="text" name="breed" placeholder="Raza">
            <input type="number" name="age" placeholder="Edad">
            <input type="number" step="0.01" name="weight" placeholder="Peso">
            <button type="submit">Agregar</button>
        </form>
        <table>
            <tr><th>ID</th><th>Nombre</th><th>Especie</th><th>Raza</th><th>Edad</th><th>Peso</th><th>Acciones</th></tr>
            <?php foreach ($pets as $pet): ?>
                <tr>
                    <td><?php echo $pet['id']; ?></td>
                    <td><?php echo $pet['name']; ?></td>
                    <td><?php echo $pet['species']; ?></td>
                    <td><?php echo $pet['breed']; ?></td>
                    <td><?php echo $pet['age']; ?></td>
                    <td><?php echo $pet['weight']; ?></td>
                    <td>
                        <a href="pets.php?edit=<?php echo $pet['id']; ?>">Editar</a>
                        <a href="pets.php?delete=<?php echo $pet['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>