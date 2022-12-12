<?php

// Show all errors (for educational purposes)
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Constanten (connectie-instellingen databank)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portfolio');

date_default_timezone_set('Europe/Brussels');

// Verbinding maken met de databank
try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindingsfout: ' . $e->getMessage();
    exit;
}

$name = isset($_POST['name']) ? (string)$_POST['name'] : '';
$message = isset($_POST['message']) ? (string)$_POST['message'] : '';
$email = isset($_POST['email']) ? (string)$_POST['email'] : '';
$via = isset($_POST['via']) ? (String)$_POST['via'] : '';
$msgName = '';
$msgMessage = '';
$msgEmail = '';
$msgVia = '';

// form is sent: perform formchecking!
if (isset($_POST['btnSubmit'])) {

    $allOk = true;

    // name not empty
    if (trim($name) === '') {
        $msgName = 'Gelieve een naam in te voeren';
        $allOk = false;
    }

    if (trim($message) === '') {
        $msgMessage = 'Gelieve een boodschap in te voeren';
        $allOk = false;
    }

    if (trim($email) === '') {
        $msgEmail = 'Gelieve een email adres in te voeren';
        $allOk = false;
    }

    if (trim($via) === '') {
        $msgVia = 'Gelieve een keuze te maken';
        $allOk = false;
    }

    // end of form check. If $allOk still is true, then the form was sent in correctly
    if ($allOk) {
        // build & execute prepared statement
        $stmt = $db->prepare('INSERT INTO messages2 (Name, Email, Message,Via, Added_on) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(array($name, $email, $message, $via, (new DateTime())->format('Y-m-d H:i:s')));

        // the query succeeded, redirect to this very same page
        if ($db->lastInsertId() !== 0) {
            header('Location: formchecking_thanks.php?name=' . urlencode($name));
            exit();
        } // the query failed
        else {
            echo 'Databankfout.';
            exit;
        }

    }

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0-">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact</title>
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet"/>
    <link href="../assets/images/mellowminds.jpg" rel="icon"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&family=Merriweather+Sans:ital,wght@0,400;0,700;1,700&family=UnifrakturMaguntia&family=Zen+Dots&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
    <nav>
        <a href="../">Thibault Viaene</a>
        <ul>
            <li>
                <a href="../">Home</a>
            </li>
            <li>
                <a href="../blog">Blog</a>
            </li>
            <li>
                <a href="../about">About</a>
            </li>
            <li>
                <a href="../projects">Projects</a>
            </li>
            <li>
                <a class="active" href="../contacts">Contact</a>
            </li>
        </ul>
    </nav>

</header>
<main>
      <div class="containerContact">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <h1>Testform</h1>
              <p class="message">Alle velden zijn verplicht, tenzij anders aangegeven.</p>

              <div>
                  <label for="name">Jouw naam</label>
                  <input type="text" id="name" name="name" value="<?php echo htmlentities($name); ?>" class="input-text"/>
                  <span class="message error"><?php echo $msgName; ?></span>
              </div>


              <div>
                  <label for="email">Jouw email</label>
                  <input type="text" id="email" name="email" value="<?php echo htmlentities($email); ?>" class="input-text"/>
                  <span class="message error"><?php echo $msgEmail; ?></span>
              </div>
                <div>
                    <fieldset>
                        <legend>How did you find me?</legend>
                        <div>
                            <input type="checkbox" name="via[]" id="via0" value="Artist">
                            <label for="via0">Artist</label>
                        </div>
                        <div>
                            <input type="checkbox" name="via[]" id="via1" value="Management">
                            <label for="via1">Management</label>
                        </div>
                        <div>
                            <input type="checkbox" name="via[]" id="via2" value="Label">
                            <label for="via2">Label</label>
                        </div>
                    </fieldset>
                    <span class="message error"><?php echo $msgVia; ?></span>
                </div>

              <div>
                  <label for="message">Boodschap</label>
                  <textarea name="message" id="message" rows="5" cols="40"><?php echo htmlentities($message); ?></textarea>
                  <span class="message error"><?php echo $msgMessage; ?></span>
              </div>

              <input type="submit" id="btnSubmit" name="btnSubmit" value="Verstuur"/>

          </form>
    </div>


</main>
<footer><p> &copy; 2022 Thibault Viaene - social media links - copyright - address</p></footer>
</body>
</html>