<?php
    session_start();
    if (isset(($_SESSION['logged'])) && ($_SESSION['logged'] == true)) {
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['login'])) {
        $correct = true;

        $login = $_POST['login'];
        $loginB = filter_var($login, FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if ($login != $loginB) {
            $correct = false;
            $_SESSION['e-login'] = "Login zawierać może jedynie litery i cyfry";
        }
        mysqli_report(MYSQLI_REPORT_STRICT);
        require_once("connection.php");
        try {
            $connection = new mysqli($hostname, $username, $dbpassword, $database);
            if ($connection->errno != 0) {
                throw new Exception(mysqli_connect_errno());
            }
            else {
                $results = $connection->query("SELECT * FROM bmail_users WHERE user = '$login'");
                if (!$results) throw new Exception($connection->error);

                if ($results->num_rows >= 1) {
                    $_SESSION['e-login'] = "Taki login już istnieje";
                    $correct = false;
                }
                $results->free_result();

                if ($correct) {
                    if ($connection->query("INSERT INTO bmail_users VALUES (null, '$login', '$password_hash')")) {
                        header('Location: register-successful.php');
                    } else {
                        throw new Exception($connection->error);
                    }
                }
                $connection->close();
            }

        }
        catch(Exception $e) {
            $_SESSION['e-login'] = "Błąd serwera! Spróbuj ponownie później.<br>Informacja dla dewelopera: $e";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarejestruj się | Bmail</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
    <script src="register.js" defer></script>
</head>
<body class="preload">
    <div id="navbar">
        <div class="navbar-left">
            <a href="index.php" class="logo"><img src="img/logo.svg" alt="Logo"></a>
        </div>
        <div class="navbar-right">
            <p>Wersja 1.0</p>
            <p>Stabilna</p>
        </div>
    </div>
    <div id="main">
        <div class="main-left">
            <h1>Utwórz konto na platformie Bmail</h1>
            <p>Jedno konto, wiele usług</p>
        </div>
        <div class="split-line"></div>
        <div class="main-right">
            <h1 class="h1-welcome">Zarejestruj się</h1>
            <div class="login-container">
                <form class="login-form" method="post">
                    <label for="login">Login: </label>
                    <input type="text" autocomplete="login" id="login" name="login" required maxlength="32">
                    <?php
                        if (isset($_SESSION['e-login'])) {
                            echo "<span class='form-error'>".$_SESSION['e-login']."</span><br>";
                            unset($_SESSION['e-login']);
                        }
                    ?>
                    <label for="pass">Hasło: </label>
                    <input type="password" autocomplete="current-password" id="pass" name="password" required>
                    <div class="login-submit">
                        <input type="submit" name="register" value="Zarejestruj się">
                    </div>
                </form>
            </div>
            <div class="register-info">
                <h3 class="h3-register">Posiadasz już konto?</h3>
                <p class="p-register">Skorzystaj z formularza logowania poniżej.</p>
            </div>
            <div class="register-buttons">
                <form action="form-actions.php" method="post">
                    <input class="button btn-register" type="submit" name="BackToLogin" value="Powrót do logowania">
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