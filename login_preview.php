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

    <section id="login-section" class="text-center">
        <form class="form-signin mx-auto" method="post" action="login_preview.php">
            <img id="loginLogo" class="mb-4" src="logo0.png" alt="" width="90" height="90">
            <h1 class="h3 mb-3 font-weight-normal">Zaloguj się</h1>
            <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
            <input type="password" name="password" class="form-control" placeholder="Hasło" required>
            <div id="reg">
                <span>Nie masz konta? </span>
                <a href="reg_preview.php">Zarejestruj się.</a>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Zaloguj</button>
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
    if(isset($_POST["email"]))  
      {
        $host = "localhost"; 
        $cookie_name = "islogged";
        $uzytkownik = "root"; 
        $baza_danych = "biuro_podrozy"; 

        $conn = new mysqli($host, $uzytkownik, "", $baza_danych);
        if ($conn->connect_error) {
            die("An error occured while connecting to the database: " . $conn->connect_error);
        }
        if(!isset($_COOKIE[$cookie_name])) {

            $cookie_value = 0;
            setcookie($cookie_name, $cookie_value, time() + 86400, "/biuro");
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $haslo = $_POST['password'];
            $query = "SELECT * FROM users WHERE email = '$email' AND password = '$haslo';";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                if ($result) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<script>customAlert('Logged in on $email account!', 'success');setTimeout(()=>window.location.replace('index.html'), 3000)</script>"; //Logged in on x account
                    }
    
                    $cookie_value = trim($email, "  ");
                    setcookie($cookie_name,$cookie_value,time()+(86400*30),"/biuro");
                } else {
                    echo "<script>customAlert('Error: " . mysqli_error()."', 'danger');</script>";
                    $cookie_value = 0;
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/biuro");
                    
                }
            } else {
                echo "<script>customAlert('User with given password and email does not exist!', 'warning');</script>"; //user does not exist
            }
           
        }

        mysqli_close($conn);
      }
    ?>


</body>

</html>

<?php
    ob_end_flush();
?>