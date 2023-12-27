<?php
require_once __DIR__ . "/utils/log.php";

$id = $_GET['idd'];
$pdo = new PDO('mysql:dbname=dwm', 'root', '');

$sql = "SELECT * FROM users WHERE id=$id";

$stmt = $pdo->query($sql);

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) header("location:/");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Manager</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/cerulean/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container">
        <h1>Modifier un utilisateur</h1>
        <form action="?action=edit" method="post">
            <input type="hidden" name="iddd" value="<?= $id ?>">
            <input type="email" name="email" placeholder="Email" value="<?= $user['email'] ?>" class="form-control mb-3">
            <input type="password" name="pass" value="<?= $user['email'] ?>" placeholder="Password" class="form-control mb-3">
            <select name="role" class="form-select mb-4">
                <option value="">Sélectionnez un rôle</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="author" <?= $user['role'] === 'author' ? 'selected' : '' ?>>Author</option>
                <option value="guest" <?= $user['role'] === 'guest' ? 'selected' : '' ?>>Guest</option>
            </select>
            <button class="btn btn-outline-primary d-block w-100 mb-4">Valider</button>
        </form>