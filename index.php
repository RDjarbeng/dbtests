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

                    $result = mysqli_query($conn,"SELECT * FROM users");
                    
                    // $table_content='';
                    while($row = mysqli_fetch_assoc($result))
                    {

                    $table_content .= getTableRow($row);
                    /* //old code 
                    "<tr>".
                    "<td><div class='d-flex px-2 py-1'><div class='d-flex flex-column justify-content-center'> <h6 class='mb-0 text-sm'>". $row['Name'] . "</h6></div></div></td>".
                    "<td> <p class='text-xs font-weight-bold mb-0'>". $row['Student ID'] ."</p></td>".
                    "<td class='align-middle text-center text-sm'> <p class='text-xs font-weight-bold mb-0'>". $row['Email'] ."</p></td>".
                    "<td class='align-middle text-center text'> <p class='text-xs font-weight-bold mb-0'>". $row['Age'] ."</p></td>".
                    "</tr>";                   
                    
                    // var_dump($table_content);
                    */
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
                      
                      $result = pg_query($conn,"SELECT * FROM users");
                      if (!$result) {
                        $error_msg.="An error occurred.<br />";
                        
                      }else{
                      while($row = pg_fetch_assoc($result))
                      {
                        $table_content .= getTableRow($row);
                      }
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
                    
                      $result = sqlsrv_query($conn,"SELECT * FROM users");

                      if (!$result) {
                        $error_msg.="Failed to connect to Microsoft SQL Server:<br />".sqlsrv_errors();
                      }else{

                      while($row = sqlsrv_fetch_array($result))
                      {
                        $table_content .= getTableRow($row);
                      }

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
                    
                    $result = oci_parse($conn,"SELECT * FROM ORACLEDB.\"users\"");
                    oci_execute($result);

                    if (!$result) {
                      $error_msg.= "An error occurred Oracle result.<br/>";
                      
                    }else {

                    while($row = oci_fetch_array($result, OCI_ASSOC+OCI_RETURN_NULLS))
                    {
                      $table_content .= getTableRow($row);
                    }

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
                try{
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
              }catch(ErrorException $e){
                $error_msg.= $e-> getMessage();

              }
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
    <div class="alert alert-danger alert-dismissible">
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
<form method="post" class="col col-lg-3 formGroup">
    <div class="row ">
      <div class="col-sm-12  mb-2">
        <input type="submit" class='btn btn-info  dButton w-100 pt-2 mb-1' name="mysql" value="NHIA - MYSQL" />
      </div>
      <div class="col-sm-12   mb-2">
        <input type="submit" class='btn btn-info dButton  w-100 pt-2 mb-1' name="postgreSQL" value="DVLA-PostgreSQL" />
      </div>
      <div class="col-sm-12  mb-2">                           
        <input type="submit" class='btn btn-info  dButton w-100 pt-2 mb-1' name="mssql" value="Immigration - Microsoft SQL" />
      </div>
      <div class="col-sm-12  mb-2">                                   
        <input type="submit" class='btn btn-info  dButton w-100 pt-2 mb-1' name="oracle" value="EC-Oracle" />
      </div>
      
    </div>

</form>

<div class="col-sm-12 col-lg-9">
<table class="list_table table ">
  <thead>
    
    <tr>
      <th>Name</th>
      <th>ID</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  if (!empty($table_content)) {
  echo $table_content;
} else { echo "";}
?>
<!-- Dummy user -->
    <!-- <tr>
      <td>John</td>
      <td>Doe</td>
      <td>john@example.com</td>
    </tr> -->
  </tbody>
</table>
</div>
</div>
  </body>
  
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>
</html>
