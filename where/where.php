<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <form method="POST">
    search by using username:
    <input placeholder="search" type="text" name="info">
    <input type="submit">
  </form>
  <table>
    <?php
    echo "<table style='border:1px solid;'>";
    echo "<tr><th>username</th><th>email</th><th>password</th></tr>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $database = "emailusers";
      $table = "users";
      $info = $_POST["info"];
      class TableRows extends RecursiveIteratorIterator
      {
        function __construct($it)
        {
          parent::__construct($it, self::LEAVES_ONLY);
        }
        function current()
        {
          return "<td style='width: 150px; border: 1px solid black;'>"
            . parent::current() . "</td>";
        }
        function beginChildren()
        {
          echo "<tr>";
        }
        function endChildren()
        {
          echo "<tr>" . "\n";
        }
      }

      try {
        $connect = new PDO("mysql:host=localhost;dbname=$database", "root", "");
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $connect->prepare("SELECT username, email, password FROM $table WHERE username='$info'");
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        foreach (new TableRows(new RecursiveArrayIterator($statement->fetchAll())) as $k => $v) {
          echo $v;
        }
      } catch (PDOException $e) {
        echo "a probleme happened" . " " . $e->getMessage();
      }
    }
    ?>
  </table>
</body>

</html>