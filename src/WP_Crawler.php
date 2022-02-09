<?php  
include_once 'includes/autoload.php';

use Classes\ConnectionClass;
use Classes\TaskClass;


$connection = new ConnectionClass();
$tasking = new TaskClass();

// crawl functionality
if (isset($_POST["action"])) 
{
	if ($_POST["action"] == "crawl")
	{
		$host_address = $_POST['host_address'];
		$connection->sql = "TRUNCATE TABLE host_tbl";
		$connection->execute_query();
		$connection->sql = "INSERT INTO host_tbl(host_url) VALUES ('$host_address')";
		$connection->execute_query();
	    if (true === $tasking->tasks()) 
	    {
	    	echo json_encode(["success" => true]);
	    }
	    else
	    {
	    	echo json_encode(["success" => false]);
	    }
	}
}

// data functionality
if (isset($_GET["action"])) 
{
	if ($_GET["action"] == "fetch")
	{
		$table = '<table id="datatablesSimple">
	                <thead>
	                    <tr>
	                        <th>Id</th>
	                        <th>URL</th>
	                        <th>Last modified (GMT)</th>
	                    </tr>
	                </thead>
	                <tfoot>
	                    <tr>
	                        <th>Id</th>
	                        <th>URL</th>
	                        <th>Last modified (GMT)</th>
	                    </tr>
	                </tfoot>
	                <tbody>';
	    $table .= $connection->fetchData();
	    $table .= '</tbody>
              </table>';
		echo json_encode(["success" => true, "result" => $table]);
	}
}

// login functionality
if (isset($_POST['action']))
{
	if ($_POST['action'] == 'login')
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		$connection->sql = "SELECT email, password, token FROM admin_tbl WHERE email = '$email'";
		$row = $connection->query_result();
	    if (password_verify($password, $row['password'])) {
		    session_start();
			$_SESSION['email'] = $row['email'];
			$_SESSION['token'] = $row['token'];
			echo json_encode(["token" => "${_SESSION['token']}", "email" => "${_SESSION['email']}"]);    
	    } 
	    else 
	    {
		    echo json_encode(["error" => "Email or Password does not exist"]);
	    }
    }   
}

// logout functionality
if (isset($_GET["action"])) 
{
	if ($_GET["action"] == "logOut")
	{
		session_start();
		if ($_GET['token'] || $_GET['email']) 
		{
			session_unset();
			session_destroy();
			echo json_encode(['success' => true]);
		}
	}
}


?>