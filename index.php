<?php
require_once __DIR__ . "/utils/log.php";
// require_once __DIR__ . "/controllers.php";

$pdo = new PDO('mysql:dbname=dwm', 'root', '');

$sql = "SELECT * FROM users";

$stmt = $pdo->query($sql);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


// des alertes 
$successMessage = "";
$warningMessage = "";



if (($_SERVER['REQUEST_METHOD']) === 'POST') {
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $pass = md5($_POST['pass']);
        $role = $_POST['role'] ? $_POST['role'] : 'guest';
        $sql = "INSERT INTO users (email, pass, role) VALUES (:email, :pass, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->execute();
        $successMessage = "Utilisateur ajouté avec succès.";
        $warningMessage = "Utilisateur non enregistrer";
        header("location:/");
        exit;
    }


    if (isset($_POST['delete'])) {
        $id = $_POST['idd'];
        $sql = "DELETE FROM users WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $successMessage = "Utilisateur supprimé avec succès.";
        $warningMessage = "Utilisateur non supprimer";
        header("location:/");
        exit;
    }

    if (isset($_POST['update'])) {
        $id = $_POST['iddd'];
        $email = $_POST['email'];
        $pass = md5($_POST['pass']);
        $role = $_POST['role'] ? $_POST['role'] : 'guest';
        $sql = "UPDATE users SET email=:email, pass=:pass, role=:role WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->execute();
        $successMessage = "Informations utilisateur mises à jour avec succès.";
        header("location:/");
        exit;
    }
}










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
        <h1>Users Manager</h1>
        <?php if ($successMessage || $warningMessage) : ?>
            <div class="container mt-3">
                <?php if ($successMessage) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $successMessage ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif ?>

                <?php if ($warningMessage) : ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?= $warningMessage ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>
        <form action="" method="post">
            <!-- <input type="hidden" name="csrf_token" value="votre_valeur_csrf"> -->
            <input type="email" name="email" placeholder="Email" class="form-control mb-3">
            <input type="password" name="pass" placeholder="Password" class="form-control mb-3">
            <select name="role" class="form-select mb-4">
                <option value="">Sélectionnez un rôle</option>
                <option value="admin">Admin</option>
                <option value="author">Author</option>
                <option value="guest">Guest</option>
            </select>
            <button class="btn btn-outline-primary d-block w-100 mb-4" name="submit" value="submit" type="submit">Ajouter</button>
        </form>
        <table class="table table-stripped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>EMAIL</th>
                    <th>PASSWORD</th>
                    <th>ROLE</th>
                    <th>Delete</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td class="text-center">
                            <?= $user['id'] ?>
                        </td>
                        <td class="text-center">
                            <?= $user['email'] ?>
                        </td>
                        <td class="text-center">
                            <?= $user['pass'] ?>
                        </td>
                        <td class="text-center">
                            <?= $user['role'] ?>
                        </td>

                        <td class="">
                            <!-- lunch modale pour supprimer  -->
                            <a type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $user['id'] ?>">
                                <i class="bi bi-trash"></i>
                            </a>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez vous supprimer user ? <?= $user['id'] ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                            <form action="" method="post">
                                                <input type="hidden" name="idd" value="<?= $user['id'] ?>">
                                                <button type="submit" class="btn btn-danger" name="delete">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>


                        <td class="">
                            <!-- button de modal pour editer -->
                            <a type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <!-- modal pour editer -->

                            <form action="" method="post">
                                <input type="hidden" name="iddd" value=<?= $user['id'] ?>">
                                <div class="modal fade" id="editModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" name="email" value="<?= $user['email'] ?>" class="form-control mb-3">
                                                <label for="pass" class="form-label">Password</label>
                                                <input type="password" name="pass" value="<?= $user['email'] ?>" class="form-control mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select name="role" class="form-select mb-4">
                                                    <option value="">Select a role</option>
                                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                    <option value="author" <?= $user['role'] === 'author' ? 'selected' : '' ?>>Author</option>
                                                    <option value="guest" <?= $user['role'] === 'guest' ? 'selected' : '' ?>>Guest</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" name="update">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>


                        </td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <script>
        // function valider(evt) {
        //     evt.preventDefault()
        //     console.log(evt.target)
        //     if (confirm("Etes-vous sûr de vouloir supprimer ?"))
        //         location.href = evt.target.closest('a').href
        // }
        // JavaScript pour déclencher la suppression
        function deleteUser(userId) {
            // Vous pouvez utiliser AJAX pour envoyer une requête de suppression au serveur
            // Ici, je vais simplement rediriger vers une URL de suppression avec le paramètre ID
            window.location.href = "?action=delete&id=" + userId;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>



<?php
log_r($users);

?>