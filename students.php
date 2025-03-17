## Código

<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
    <title>Students Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>Students Page</h1>

<?php
  /* Conectar ao MySQL e selecionar o banco de dados */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Criar a tabela STUDENTS se não existir */
  VerifyStudentsTable($connection, DB_DATABASE);

  /* Adicionar um novo estudante se os campos estiverem preenchidos */
  if (!empty($_POST['NAME']) && !empty($_POST['AGE']) && !empty($_POST['COURSE']) && !empty($_POST['GRADE'])) {
      $student_name = htmlentities($_POST['NAME']);
      $student_age = intval($_POST['AGE']);
      $student_course = htmlentities($_POST['COURSE']);
      $student_grade = floatval($_POST['GRADE']);

      AddStudent($connection, $student_name, $student_age, $student_course, $student_grade);
  }
?>

<!-- Formulário para adicionar estudantes -->
<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
  <table>
    <tr>
      <th>Name</th>
      <th>Age</th>
      <th>Course</th>
      <th>Grade</th>
      <th>Action</th>
    </tr>
    <tr>
      <td><input type="text" name="NAME" required></td>
      <td><input type="number" name="AGE" required></td>
      <td><input type="text" name="COURSE" required></td>
      <td><input type="number" step="0.01" name="GRADE" required></td>
      <td><input type="submit" value="Add Student"></td>
    </tr>
  </table>
</form>

<!-- Tabela de alunos cadastrados -->
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Age</th>
    <th>Course</th>
    <th>Grade</th>
  </tr>

<?php
$result = mysqli_query($connection, "SELECT * FROM students");

while ($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>{$query_data[0]}</td>";
  echo "<td>{$query_data[1]}</td>";
  echo "<td>{$query_data[2]}</td>";
  echo "<td>{$query_data[3]}</td>";
  echo "<td>{$query_data[4]}</td>";
  echo "</tr>";
}

mysqli_free_result($result);
mysqli_close($connection);
?>

</table>

<?php
/* Função para adicionar estudante */
function AddStudent($connection, $name, $age, $course, $grade) {
   $n = mysqli_real_escape_string($connection, $name);
   $a = intval($age);
   $c = mysqli_real_escape_string($connection, $course);
   $g = floatval($grade);

   $query = "INSERT INTO students (NAME, AGE, COURSE, GRADE) VALUES ('$n', $a, '$c', $g);";

   if (!mysqli_query($connection, $query)) {
       echo "<p>Error adding student data.</p>";
   }
}

/* Função para verificar se a tabela existe e criá-la se necessário */
function VerifyStudentsTable($connection, $dbName) {
  if (!TableExists("students", $connection, $dbName)) {
    $query = "CREATE TABLE students (
        ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        NAME VARCHAR(100),
        AGE INT(3),
        COURSE VARCHAR(100),
        GRADE DECIMAL(4,2)
    );";

    if (!mysqli_query($connection, $query)) {
        echo "<p>Error creating table.</p>";
    }
  }
}

/* Função para verificar a existência da tabela */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  return mysqli_num_rows($checktable) > 0;
}
?>

</body>
</html>
```php
