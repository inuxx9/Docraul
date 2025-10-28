<?php
// recepcionista/asignar_dentista.php

// Inclusión del chequeo de acceso y la conexión a la BD
include 'auth_check.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: panel_citas.php');
    exit;
}

$cita_id = $_POST['cita_id'] ?? null;
$dentista_id = $_POST['dentista_id'] ?? null;

if (empty($cita_id) || empty($dentista_id)) {
    header('Location: panel_citas.php?error=' . urlencode('Error: Datos de cita o dentista incompletos.'));
    exit;
}

try {
    // 1. Actualizar la cita en la BD
    $sql = "UPDATE citas SET estado = 'Confirmada', dentista_id = :dentista_id WHERE cita_id = :cita_id AND estado = 'Pendiente'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':dentista_id' => $dentista_id,
        ':cita_id' => $cita_id
    ]);

    if ($stmt->rowCount() > 0) {
        // Éxito: Redirigir con un mensaje
        header('Location: panel_citas.php?mensaje=' . urlencode('Cita confirmada y asignada con éxito.'));
    } else {
        // Fallo: Cita no encontrada o ya procesada
        header('Location: panel_citas.php?error=' . urlencode('La cita no pudo ser confirmada (ya procesada o ID incorrecto).'));
    }
    exit;

} catch (PDOException $e) {
    // Error de BD
    error_log("Error al asignar dentista: " . $e->getMessage());
    header('Location: panel_citas.php?error=' . urlencode('Error de la base de datos: ' . $e->getMessage()));
    exit;
}
?>