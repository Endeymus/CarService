<?php
require_once("../functions/database_request.php");

    $id_employee = sql_find_free_employee();
if (isset($_POST['free-employees'])) {
    $id_employee = sql_find_free_employee();
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include '../blocks/head.php' ?>

<body>
<?php include '../blocks/header.php' ?>

<?php
echo "Приветь, я Катуся"
?>

<div class="container">
    <form class="row g-3">
        <div class="col-auto">
            <label for="staticEmail2" class="visually-hidden">Email</label>
            <input type="text" readonly class="form-control-plaintext" id="staticEmail2"
                   value="'<?php echo $id_employee == 0 ? 'Нет свободных работников' : $id_employee ?>'">
        </div>
        <!--        <div class="col-auto">
                    <label for="inputPassword2" class="visually-hidden">Password</label>
                    <input type="password" class="form-control" id="inputPassword2" placeholder="Password">
                </div>-->
        <div class="col-auto">
            <button id="free-employees" type="submit" class="btn btn-outline-success mb-3">Confirm identity</button>
        </div>
    </form>
</div>


</body>
</html>