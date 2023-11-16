<?php
    ob_start();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="logo0.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travebyl</title>
    <link rel="stylesheet" href="style.css">
</head>

<body onload="dateValidation();">
<div id="alertPlaceholder"></div>
    <nav class="navbar fixed-top" style="height: 7vh;">
        <div class="container-fluid" style="background-color: inherit;">
            <a href="index.html" class="navbar-brand" width="5%">
                <img src="logo0.png" width="50" height="50" alt="LOGO" id="logo">
            </a>
            <div class="row">
                <div class="col">
                    <span width="50" height="50" id="darkmode" class="clickable_span material-icons">
                        &#xe51c;
                    </span>
                </div>
                <div class="col">
                    <span id="logBtn" width="50" height="50" onclick="requireBeingLogged(logout)"
                        class="clickable_span material-icons">
                        &#xe853;
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <section id="res-section" class="text-center">
        <form class="form-signin mx-auto" action="res_preview.php" method="post">
            <img id="loginLogo" class="mb-4" src="logo0.png" alt="" width="90" height="90">
            <h1 class="h3 mb-3 font-weight-normal">Zarezerwuj wyprawę</h1>
            <input name="howManyPpl" type="number" min="1" max="4" class="form-control" placeholder="Ile osób"
                onchange="price()" required autofocus>
            <input id="datefield" name="from" type="date" class="form-control" placeholder="Od kiedy" required>
            <label for="isInsurance">Czy chcesz ubezpieczenie:</label>
            <input name="isInsurance" type="checkbox" class="form-check-input" onchange="price()">
            <br>
            <hr style="height:5px; color: var(--ft-color-reversed); background-color: inherit !important;">
            <button id="priceBtn" class="btn btn-lg btn-primary btn-block" type="submit">Zarezerwuj</button>
        </form>
    </section>

    <footer class="footer mt-auto py-3">
        <b>Made with passion by:</b> Szymon Czyżewski & Albert Halusiak. <b>Tel:</b> 111 222 333 <b>E-mail:</b>
        travebyl@tb.com
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <?php
      if(isset($_POST["howManyPpl"]))
      {
        $host = "localhost"; 
        $uzytkownik = "root"; 
        $baza_danych = "biuro_podrozy"; 
        
        $conn = new mysqli($host, $uzytkownik, "", $baza_danych);
        if ($conn->connect_error) {
            die("An error occured while connecting to the database: " . $conn->connect_error);
        }
        if (isset($_POST["howManyPpl"])&&isset($_POST["from"])) {
            if (filter_has_var(INPUT_POST, 'isInsurance'))
            {
              $insurance=1;
            }
            else
            {
              $insurance=0;
            }

            $iloscosob=$_POST["howManyPpl"];
            $data = date('Y-m-d', strtotime($_POST["from"]));
            $type=$_COOKIE["tripKind"];

            $query = "SELECT user_id FROM users WHERE email = '".$_COOKIE["islogged"]."'";
            $user_id = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($user_id);
            if(!is_null(@$row["user_id"])){
              $real_user_id = $row["user_id"];

              $price=(($insurance ? 800 : 0) * $iloscosob) +($iloscosob  * ($type == 0 ? 417 : 1687));
              $sql = "INSERT INTO trips (number_of_tickets, date, type, insurance, price, user_id)
              VALUES ($iloscosob,'$data','$type',$insurance, $price, $real_user_id)";
              if($real_user_id)$result = mysqli_query($conn,$sql);
              if ($result) {
                echo "<script>customAlert('Trip registered.', 'success');setTimeout(()=>window.location.replace('index.html'), 3000)</script>";
              } else {
                echo "<script>customAlert('Error: " . mysqli_error()."', 'danger');</script>";
              }
            }



        }else{echo"<script>customAlert('Not enough data!', 'warning');</script>";}
        mysqli_close($conn);

      }
    ?>


</body>

</html>
<?php
    ob_end_flush();
?>