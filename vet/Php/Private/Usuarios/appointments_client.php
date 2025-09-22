<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 4) {
    header('Location: index.php');
    exit;
}
$client_id = $_SESSION['user_id'];

// Get pets
$stmt = $pdo->prepare("SELECT * FROM pets WHERE owner_id = ?");
$stmt->execute([$client_id]);
$pets = $stmt->fetchAll();

// Get vets
$stmt = $pdo->query("SELECT * FROM users WHERE role = 2");
$vets = $stmt->fetchAll();

// Create appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pet_id = $_POST['pet_id'];
    $vet_id = $_POST['vet_id'];
    $date_time = $_POST['date_time'];

    // Check availability (simple: no overlap)
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE vet_id = ? AND date_time = ?");
    $stmt->execute([$vet_id, $date_time]);
    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO appointments (pet_id, vet_id, date_time) VALUES (?, ?, ?)");
        $stmt->execute([$pet_id, $vet_id, $date_time]);
    } else {
        echo "Horario no disponible.";
    }
}

// Read appointments
$stmt = $pdo->prepare("SELECT a.*, p.name as pet_name, u.name as vet_name FROM appointments a JOIN pets p ON a.pet_id = p.id JOIN users u ON a.vet_id = u.id WHERE p.owner_id = ?");
$stmt->execute([$client_id]);
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agendar Citas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Agendar Cita</h1>
        <form method="POST">
            <select name="pet_id" required>
                <?php foreach ($pets as $pet): ?>
                    <option value="<?php echo $pet['id']; ?>"><?php echo $pet['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="vet_id" required>
                <?php foreach ($vets as $vet): ?>
                    <option value="<?php echo $vet['id']; ?>"><?php echo $vet['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="datetime-local" name="date_time" required>
            <button type="submit">Agendar</button>
        </form>
        <h2>Citas</h2>
        <table>
            <tr><th>ID</th><th>Mascota</th><th>Veterinario</th><th>Fecha/Hora</th><th>Estado</th></tr>
            <?php foreach ($appointments as $app): ?>
                <tr>
                    <td><?php echo $app['id']; ?></td>
                    <td><?php echo $app['pet_name']; ?></td>
                    <td><?php echo $app['vet_name']; ?></td>
                    <td><?php echo $app['date_time']; ?></td>
                    <td><?php echo $app['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>