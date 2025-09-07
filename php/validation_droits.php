<?php


if (!isset($_SESSION['user']) || !isset($_SESSION['user']['droits'])) {
    header('Location: /login.php');
    exit;
}

$user_droits = $_SESSION["droit"];

if ($user_droits === 'cercleux' || $user_droits === 'cercle') {
    $autorise = true;
}

if (!$autorise) {
    http_response_code(403);
    echo "Accès refusé.";
    exit;
}
?>
