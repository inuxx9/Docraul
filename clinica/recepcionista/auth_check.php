<?php
// recepcionista/auth_check.php

// ----------------------------------------------------------------------
// IMPORTANTE: SIMULACIÓN DE ACCESO DE RECEPCIONISTA (SOLO PARA PRUEBAS)
// En un sistema real, esta línea estaría protegida por un LOGIN.
// Si deseas ver el panel, DESCOMENTA la siguiente línea:
 $acceso_concedido = true; 
// ----------------------------------------------------------------------

if (!isset($acceso_concedido) || $acceso_concedido !== true) {
    // Si no tienes una lógica de autenticación real, descomenta la línea de arriba.
    // En producción, esto redirigiría a un login: header('Location: ../login.php');
    die("⛔️ ACCESO DENEGADO. Este es un panel exclusivo. Implemente un sistema de autenticación real.");
}

require_once '../config/db.php';
?>