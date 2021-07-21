<?php
$error_msg ="";
$table_content ="";
set_error_handler(function($errno, $errstr, $errfile, $errline) {
  // error was suppressed with the @-operator
  if (0 === error_reporting()) {
      return false;
  }
  
  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

function runMySQLFunction() {
                  global $error_msg;
                  global $table_content;
                  // Connecting to mysql database
                  
                  $conn = new mysqli("localhost", "root", "", "sqldb");
                  // Check for database connection error
                  if (mysqli_connect_errno()) {
                  $error_msg."Failed to connect to MySQL: " . mysqli_connect_error()."<br />" ;
                  } else {
                      $email = $_POST['email'];
                      $name = $_POST['name'];
                      $age = $_POST['age'];
                      $student_id = $_POST['student_id'];
                    // $result = mysqli_query($conn,"SELECT * FROM users");

                    $sql = "INSERT INTO users (name,  email, student_id, age )
VALUES ($name, $email, $student_id, $age)";

if ($conn->query($sql) === TRUE) {
  $error_msg.= "New record created successfully";
} else {
  $error_msg.= "Error: " . $sql . "<br>" . $conn->error;
}
                    mysqli_close($conn);
                  }
                
                }

                function runpostgreSQLFunction() {
                  global $error_msg;
                  global $table_content;
                  // Connecting to postgresql database
                  
                  $conn = pg_connect("host=localhost dbname=postgresql user=postgres password=1234");
                  // Check for database connection error
                    if( $conn ) {
                        $email = $_POST['email'];
                        $name = $_POST['name'];
                        $age = $_POST['age'];
                        $student_id = $_POST['student_id'];
                        $query = "INSERT INTO users (name,  email, student_id, age ) VALUES ('$email','$name','$student_id','$age')";
                        $result = pg_query($query);
                      if (!$result) {
                        $error_msg.="An error occurred.<br />";
                        
                      }else{
                        $error_msg.="Successful row creation.<br />";
                    }

                                   
                  } else {
                  $error_msg.="Failed to connect to PostgreSQL:<br />";
                  }
                
                }

                function runmsSQLFunction() {
                  global $error_msg;
                  global $table_content;
                  
                  // Connecting to mssql database
                  $serverName = 'localhost\sqlexpress'; //serverName\instanceName

                  // Since UID and PWD are not specified in the $connectionInfo array,
                  // The connection will be attempted using Windows Authentication.
                  $connectionInfo = array( "Database"=>"mssql" );
                  $conn = sqlsrv_connect( $serverName, $connectionInfo);
                  if( $conn ) {
                    
                    $email = $_POST['email'];
                    $name = $_POST['name'];
                    $age = $_POST['age'];
                    $student_id = $_POST['student_id'];
                      $result = sqlsrv_query($conn,"INSERT INTO users VALUES ('$student_id','$name','$email','$age')");

                      if (!$result) {
                        $error_msg.="Failed to connect to Microsoft SQL Server:<br />".var_dump(sqlsrv_errors());
                      }else{
                        $error_msg.="Successful row creation.<br />";
                      sqlsrv_free_stmt( $result);
                      sqlsrv_close( $conn );
                    }
                  

                  } else {
                  $error_msg.="Failed to connect to Microsoft SQL Server:<br />".sqlsrv_errors();
                //   die( print_r( sqlsrv_errors(), true));
                  }
                
                }
                //todo
                function runoracleFunction() {
                  global $error_msg;
                  global $table_content;
                  // Connecting to oracle database
                  
                  $conn = oci_connect('system', '1234', 'localhost/XE');
                  if( $conn ) {
                    
                    $email = $_POST['email'];
                    $name = $_POST['name'];
                    $age = $_POST['age'];
                    $student_id = $_POST['student_id'];
                    //(student_id, name, email, age )
                    $result = oci_parse($conn,"INSERT INTO ORACLEDB.\"users\" VALUES ('$student_id','$name','$email','$age')");
                    oci_execute($result);

                    if (!$result) {
                      $error_msg.= "An error occurred Oracle result.<br/>";
                      
                    }else {

                        $error_msg.="Successful row creation.<br />";
                    oci_free_statement($result);
                    oci_close($conn);
                  }
                  } else {
                  $error_msg.= "Failed to connect to Oracle:<br />";
                  }
                }

                function getTableRow($row){
                  return <<<INPUT
                                    <tr>
                                    <td> {$row["name"]}</td>
                                    <td>{$row["student_id"]}</td>
                                    <td>{$row['email']} </td>
                                  </tr>
INPUT;
                }
                // try{
                if (array_key_exists('mysql', $_POST)) {
                  runMySQLFunction();
                }

                if (array_key_exists('postgreSQL', $_POST)) {
                   runpostgreSQLFunction();
                 }

                 if (array_key_exists('mssql', $_POST)) {
                   runmsSQLFunction();
                 }

                 if (array_key_exists('oracle', $_POST)) {
                  runoracleFunction();
                }
            //   }catch(ErrorException $e){
            //     $error_msg.= $e-> getMessage();

            //   }
              ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> Animated RD TPN</title>
    <link rel="stylesheet" href="css/resetCss.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="style.css">

  </head>
  <body>
<div>
  <?php 
  if (!empty($error_msg)) {
    echo <<<ERROR
    <div class="alert alert-success alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  $error_msg
</div>
ERROR
;
  }
  ?>
  
</div>

<div class="row container-fluid pt-3">
  <!-- Buttons -->
<form method="post" class="row formGroup">
    <div class="row  col-sm-3">
      <div class="col-sm-12  mb-2">
        <input type="submit" class='submit_btn w-100 ' name="mysql" value="NHIA - MYSQL" />
      </div>
      <div class="col-sm-12   mb-n">
        <input type="submit" class='submit_btn w-100 ' name="postgreSQL" value="DVLA-PostgreSQL" />
      </div>
      <div class="col-sm-12  mb-2">                           
        <input type="submit" class='submit_btn w-100 ' name="mssql" value="Immigration - Microsoft SQL" />
      </div>
      <div class="col-sm-12  mb-2">                                   
        <input type="submit" class='submit_btn w-100 ' name="oracle" value="EC-Oracle" />
      </div>
      </div>
      <div class="box col-sm-9 col-lg-9" action="index.html" method="post">
  <h1>Enter user</h1>
  <div><input type="text" name="student_id" placeholder="ID"></div>
  <div><input type="text" name="name" placeholder="Name"></div>
  <div><input type="text" name="email" placeholder="Email"></div>
  <div><input type="text" name="age" placeholder="Age"></div>
</div>
    
    <a href="/dbtests/index.php">
    <div class="btn btn-primary">
  Back to dashboard
</div>
</a>
</form>



</div>
  </body>
  
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>
</html>
