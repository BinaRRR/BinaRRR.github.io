<?php
    session_start();
    if (isset(($_SESSION['logged'])) && ($_SESSION['logged'] == true)) {
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['login']) && isset($_POST['password'])) {
        require_once('connection.php');
        $connection = @new mysqli($hostname, $username, $dbpassword, $database);

        if ($connection->connect_errno!=0)
        {
            echo "Error: ".$polaczenie->connect_errno;
        }
        else
        {
            $login = $_POST['login'];
            $password = $_POST['password'];
            
            $login = str_replace("@bmail.com", "", htmlentities($login, ENT_QUOTES, "UTF-8"));
            $password = htmlentities($password, ENT_QUOTES, "UTF-8");
        
            if ($results = @$connection->query(
            sprintf("SELECT * FROM bmail_users WHERE user='%s'",
            mysqli_real_escape_string($connection,$login)))) {
                if ($results->num_rows >= 1) {
                    $row = $results->fetch_assoc();
                    if (password_verify($password, $row['pass'])) {
                        $_SESSION['logged'] = true;

                        $_SESSION['id'] = $row['ID'];
                        $_SESSION['user'] = $row['user'];
                        $results->free_result();
                        header("Location: index.php");
                    }
                    else {
                        $_SESSION['e-password'] = "Niepoprawny login lub hasło.";
                    }
                }
                else {
                    $_SESSION['e-password'] = "Niepoprawny login lub hasło.";
                }
            }
        }
        $connection->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaloguj się | Bmail</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
</head>
<body class="preload">
    <div id="navbar">
        <div class="navbar-left">
            <a href="./index.php" class="logo"><img src="img/logo.svg" alt="Logo"></a>
        </div>
        <div class="navbar-right">
            <p>Wersja 1.0</p>
            <p>Stabilna</p>
        </div>
    </div>
    <div id="main">
        <div class="main-left">
            <h1>Zaloguj się do swojego konta Bmail</h1>
            <p>Jedno konto, wiele usług</p>
        </div>
        <div class="split-line"></div>
        <div class="main-right">
            <h1 class="h1-welcome">Witaj ponownie!</h1>
            <div class="login">
                <form class="login-form" method="post">
                    <label for="login">Login: </label>
                    <input type="text" autocomplete="login" id="login" name="login" required >
                    
                    <label for="pass">Hasło: </label>
                    <input type="password" autocomplete="current-password" id="pass" name="password" required>
                    <?php
                        if (isset($_SESSION['e-password'])) {
                            echo "<span class='form-error'>".$_SESSION['e-password']."</span>";
                            unset($_SESSION['e-password']);
                        }
                    ?>
                    <div class="login-submit">
                        <input type="submit" value="Zaloguj">
                    </div>
                </form>
            </div>
            <div class="register-info">
                <h3 class="h3-register">Nie masz jeszcze konta?</h3>
                <p class="p-register">Nie zwlekaj i utwórz je już dziś! To nic nie kosztuje.</p>
            </div>
            <div class="register-buttons">
                <form action="form-actions.php" method="post">
                    <input class="button btn-register" type="submit" name="CreateAccount" value="Utwórz konto">
                    <input class="button btn-forgot-password" type="submit" name="ForgotPassword" value="Zapomniałem hasła">
                </form>
            </div>
        </div>
    </div>
    <div class="usage">
        <div class="usage-top">
            <h1 class="h1-usage-title">Zobacz co oferujemy</h1>
            <h3 class="h3-usage-desc">Bmail jest dostosowany dla każdego typu użytkownika</h3>
        <div class="usage-list">
            <div class="usage-left">
                <div class="usage-hover">
                    <p class="p-usage-hover">Użytkownicy indywidualni mogą w pełni wykorzystywać usługę Bmail bez żadnych kosztów.</p>
                </div>
                <h2 class="h2-usage">Dla użytkowników indywidualnych</h2>
            </div>
            <div class="split-line"></div>
            <div class="usage-right">
                <div class="usage-hover">
                    <p class="p-usage-hover">Firmy i szkoły mogą zapewnić swoim pracownikom/uczniom dostęp do grup, by kategoryzować użytkowników, do których wysyłane są wiadomości mailowe.</p>
                </div>
                <h2 class="h2-usage">Dla firm i szkół</h2>
            </div>
        </div>
    </div>
    <footer>
    </footer>
</body>
</html>