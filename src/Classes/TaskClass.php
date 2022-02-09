<?php 
namespace Classes;

use Classes\ConnectionClass;
use Classes\CrawlerClass;

class TaskClass 
{
	private $connection;
	public $crawler;
    public function __construct () 
	{
		$this->connection = new ConnectionClass();
		$this->crawler = new CrawlerClass();
	}

	public function tasks (): bool
	{
		$this->connection->sql = "SELECT host_url FROM host_tbl";
		$row = $this->connection->query_result();
	    $crawled_url =  $this->crawler->crawl($row['host_url']);
		if (!empty($crawled_url)) 
		{
			$this->connection->sql = "TRUNCATE TABLE crawler_tbl";
			$this->connection->execute_query();
            /*Insert internal sub link into database*/
			$current_date_time = date("Y-m-d H:i:s");
	    	foreach($crawled_url as $url)
	    	{
				$this->connection->sql = "INSERT INTO crawler_tbl(url, date_time) VALUES ('$url', '$current_date_time')";
				$result = $this->connection->execute_query();
			}
			if ($result) {
				return true;
			}
            
		}		
		return false;
	}

}


