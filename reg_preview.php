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

<body>
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
                    <span width="50" height="50" onclick="requireBeingLogged(logout)"
                        class="clickable_span material-icons">
                        &#xe853;
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <section id="reg-section" class="text-center">
        <form class="form-signin mx-auto" action="reg_preview.php" method="post">
            <img id="loginLogo" class="mb-4" src="logo0.png" alt="" width="90" height="90">
            <h1 class="h3 mb-3 font-weight-normal">Zarejestruj się</h1>
            <input name="rfirstname" type="text" class="form-control" placeholder="Imię" required autofocus>
            <input name="rsurname" type="text" class="form-control" placeholder="Nazwisko" required>
            <input name="remail" type="email" class="form-control" placeholder="Email" required>
            <input name="rphone_number" type="text" class="form-control" placeholder="Numer Telefonu" required>
            <input name="rage" type="number" class="form-control" min="0" max="200" placeholder="Wiek" required>
            <input name="rpassword" type="password" class="form-control" placeholder="Hasło" required>
            <input name="repeat_password" type="password" class="form-control" placeholder="Powtórz hasło" required>
            <div id="reg">
                <span>Masz już konto? </span>
                <a href="login_preview.php">Zaloguj się.</a>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Zarejestruj</button>
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

    if(isset($_POST["rfirstname"]))
      {
        $host = "localhost"; 
        $uzytkownik = "root"; 
        $baza_danych = "biuro_podrozy"; 
        $conn = new mysqli($host, $uzytkownik, "", $baza_danych);
        if ($conn->connect_error) {
            die("An error occured while connecting to the database: " . $conn->connect_error);
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $haslo=$_POST["rpassword"];
            $imie = $_POST["rfirstname"];
            $nazwisko = $_POST["rsurname"];
            $wiek = $_POST["rage"];
            $email = $_POST["remail"];
            $numertel=$_POST["rphone_number"];
            $haslopowt=$_POST["repeat_password"];
            if($haslo!=$haslopowt){
              echo "<script>customAlert('Passwords aren't identical!', 'warning');</script>";//Wrong password
            }else{
              if(strlen($email)>1&&strlen($email)<256){
                  if(str_contains($email, '@')){
                    if(is_numeric($numertel)){
            $sql = "INSERT INTO users (email, password, firstname, surname, age, phone_number)
            VALUES ('$email','$haslo','$imie', '$nazwisko', $wiek, $numertel)";
            $query = "SELECT * FROM users WHERE email = '".$email."';";
            $result = mysqli_query($conn, $query);
            if ($result) {
              if (mysqli_num_rows($result) > 0) {
                echo "<script>customAlert('User already exits!', 'warning');</script>";//User already exists!
              } else {
                if (mysqli_query($conn, $sql) === TRUE) {
                    echo "<script>customAlert('User registered!', 'success');setTimeout(()=>window.location.replace('index.html'), 3000)</script>";//User registered.
                } else {
                    echo "<script>customAlert('An error of command ".$sql." occured: " . $conn->error.";', 'danger')</script>";
                }
              }
            } else {
              echo "<script>customAlert('Error: ".mysqli_error()."', 'danger');</script>";
            }

          
          }else {
            echo "<script>customAlert('Entered phone number is not a number', 'warning');</script>";//Phone is not a number
        }
    }else {
        echo "<script>customAlert('Email does not contain an \"at\" (@)!', 'warning');</script>";//Email does not contain an "at" (@)
        
    }
        } else {
          echo "<script>customAlert('Email length is too long or too short (2-255 chars)', 'warning');</script>";//Email is too long/short
        }
        
        }}

        mysqli_close($conn);
      }
    ?>


</body>

</html>
<?php
    ob_end_flush();
?>