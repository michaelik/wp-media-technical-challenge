<?php 
namespace Classes;

use Classes\ConnectionClass;
use Classes\CrawlerClass;
use Classes\SitemapClass;

class TaskClass 
{
	private $connection;
	public $crawler;
    public function __construct () 
	{
		$this->connection = new ConnectionClass();
		$this->crawler = new CrawlerClass();
		$this->sitemap = new SitemapClass();
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
            /*Check if file exist or not otherwise delete file or suppress error in case file does not exist, and create file*/
			if (is_file('upload/sitemap/sitemap.html') || !is_file('upload/sitemap/sitemap.html')) 
			{
				@unlink('upload/sitemap/sitemap.html');
				if (true === $this->sitemap->generateSiteMap())
				{
					return true;
				}
			}
		}		
		return false;
	}

}


