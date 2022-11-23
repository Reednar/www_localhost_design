<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <?php 
        $files = scanCurrentDirectory(); 
    ?>
    <div class="container mt-5">
        <h1>Liste des projets</h1>
        <h5>Rafraichir la page après la suppression ou la création d'un projet : <a href="/">rafraichir</a></h5>
        <form method="post" action="/">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" class="form-control" name="nameproject" placeholder="Nom du projet">
                    </div>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-primary" name="createproject">Nouveau projet</button>
                </div>

            </div>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Projet</th>
                    <th scope="col">Dernière modification</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file) : ?>
                    <tr>
                        <td scope="row"><?= "<a href='/$file'>$file</a>" ?></td>
                        <td><?= getLastModificationDate($file) ?></td>
                        <td>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirm_delete_project<?= $file ?>">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                    <?php
                    $files_in_project = scanProjectDirectory($file);
                    ?>
                    <div class="modal fade" id="confirm_delete_project<?= $file ?>" tabindex="-1" aria-labelledby="confirm_delete_project" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirm_delete_project">Suppression du projet</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer le projet <strong><?= $file ?></strong> ?</p>
                                    <p>Attention, cette action est irréversible !</p>
                                    <p>Les fichiers suivants seront supprimés :</p>
                                    <?php

                                    foreach ($files_in_project as $file_in_project) {
                                        echo "$file_in_project <br>";
                                    }

                                    ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <form method="post" action="/">
                                        <button type="submit" class="btn btn-primary" name="deleteproject<?= $file ?>">Confirmer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    if (isset($_POST["deleteproject$file"])) {
                        deleteProject($file);
                    }
                endforeach;
                ?>
            </tbody>
        </table>
        <h5>Rafraichir la page après la suppression ou la création d'un projet : <a href="/">rafraichir</a></h5>
    </div>
</body>

</html>

<?php

## Scan the current directory for files
function scanCurrentDirectory()
{
    $files = scandir(getcwd());
    $files = array_diff($files, array('.', '..', 'index.php'));
    return $files;
}

## Get last modification date of a file
function getLastModificationDate($file)
{
    $date = date("d/m/Y H:i:s", filemtime($file));
    return $date;
}

## Scan the directory to project clicked
function scanProjectDirectory($project)
{
    $files = scandir($project);
    $files = array_diff($files, array('.', '..'));
    return $files;
}

## Delete a project and all its files
function deleteProject($project)
{
    $files = scandir($project);
    $files = array_diff($files, array('.', '..'));
    foreach ($files as $file) {
        unlink($project . '/' . $file);
    }
    rmdir($project);
}

if (isset($_POST['nameproject'])) {
    $nameproject = $_POST['nameproject'];
    mkdir($nameproject);
    // Create file index.php in the new project
    $file = fopen($nameproject . "/index.php", "w");
    $content = "<?php echo 'Hello World'; ?>";
    fwrite($file, $content);
    fclose($file);
}
?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>