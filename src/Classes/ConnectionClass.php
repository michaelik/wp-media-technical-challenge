<?php  
namespace Classes;
use mysqli;

class ConnectionClass extends mysqli 
{
	private $host="localhost", $username="root", $password="", $dbname="WP_crawler";
	public $con;
	public $sql;
	public $statement;
	public function __construct () 
	{
		$this->con = $this->connect($this->host, $this->username, $this->password, $this->dbname);
	}
	
	public function execute_query ()
	{
		$this->statement = $this->query($this->sql);
		return $this->statement;
	}

	public function total_row ()
	{
		$this->execute_query();
		return $this->statement->num_rows;
	}

	public function query_result ()
	{
		$this->execute_query();
		return $this->statement->fetch_assoc();
	}

	public function fetchData (): string
	{
        $autoIncrement = 1;
	    $this->sql = "SELECT url, date_time FROM crawler_tbl ORDER BY id ASC";
	    $result = $this->execute_query();
	 	$data = "";
		while(null !== ($row = $result->fetch_assoc()))
		{
			$data .= '<tr class="high">
                        <td>'.$autoIncrement++.'</td>
                        <td><a href="'.$row['url'].'">'.$row['url'].'</a></td>
                        <td>'.$row['date_time'].'</td>
                      </tr>';
		}
        return $data;         
	}

}

