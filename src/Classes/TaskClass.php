<?php 
/**
 * This file facilitates the Task-specific functionality of the application.
 *
 * PHP version 7
 * 
 * @category Mycategory
 * 
 * @package MyPackage
 *
 * @author Michael Ikechikwu <mikeikechi3@gmail.com>
 *
 * @license GPL-2.0+ <https://spdx.org/licenses/GPL-2.0+>
 *
 * @link https://www.linkedin.com/in/ikechukwu-michael-330624166/ 
 *
 * @since 1.0.0 
 */

namespace Classes;

use Classes\ConnectionClass;
use Classes\CrawlerClass;
use Classes\SitemapClass;
/**
 * Define the TaskClass and set its functionality.
 *
 * PHP version 7
 * 
 * @category Mycategory
 * 
 * @package MyPackage
 *
 * @author Michael Ikechikwu <mikeikechi3@gmail.com>
 *
 * @license GPL-2.0+ <https://spdx.org/licenses/GPL-2.0+>
 *
 * @link https://www.linkedin.com/in/ikechukwu-michael-330624166/ 
 *
 * @since 1.0.0 
 */
class TaskClass
{
    private $_connection;
    public $crawler;

    /**
     * Establish connection to the database
     * Initialize the crawler and sitemap
     *
     * @since 1.0.0
     */
    public function __construct() 
    {
        $this->_connection = new ConnectionClass();
        $this->crawler = new CrawlerClass();
        $this->sitemap = new SitemapClass();
    }
    
    /**
     * Initialize the tasks and set its functionality
     *
     * @return bool
     */
    public function tasks(): bool
    {
        $this->_connection->sql = "SELECT host_url FROM host_tbl";
        $row = $this->_connection->queryResult();
        $crawled_url =  $this->crawler->crawl($row['host_url']);
        if (!empty($crawled_url)) {
            $this->_connection->sql = "TRUNCATE TABLE crawler_tbl";
            $this->_connection->executeQuery();
            /*Insert internal sub link into database*/
            $current_date_time = date("Y-m-d H:i:s");
            foreach ($crawled_url as $url) {
                $this->_connection->sql = "INSERT INTO crawler_tbl(url, date_time) 
                                          VALUES ('$url', '$current_date_time')";
                $result = $this->_connection->executeQuery();
            }
            /*Check if file exist or not otherwise delete file or 
            suppress error in case file does not exist, and create file*/
            if (is_file('upload/sitemap/sitemap.html') 
                || !is_file('upload/sitemap/sitemap.html')
            ) {
                @unlink('upload/sitemap/sitemap.html');
                if (true === $this->sitemap->generateSiteMap()) {
                    return true;
                }
            }
        }        
        return false;
    }

}


