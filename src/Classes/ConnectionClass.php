<?php  
/**
 * This file facilitates the establishment of connection to the database, 
 * with a collection of methods to perform action on the database of
 * the application.
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

use mysqli;
/**
 * The ConnectionClass inherit mysql built-in php class to establish the connection.
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
class ConnectionClass extends mysqli
{
    private $_host = "localhost", 
            $_username = "root", 
            $_password = "", 
            $_dbname = "WP_crawler";
    public $con;
    public $sql;
    public $statement;

    /**
     * Establish connection to the database
     *
     * @since 1.0.0
     */
    public function __construct() 
    {
        $this->con = $this->connect(
            $this->_host, 
            $this->_username, 
            $this->_password, 
            $this->_dbname
        );
    }
    
    /**
     * Execute the sql query
     *
     * @return bool
     */
    public function executeQuery()
    {
        $this->statement = $this->query($this->sql);
        return $this->statement;
    }
    
    /**
     * Gets the number of rows 
     *
     * @return integer
     */
    public function totalRow()
    {
        $this->executeQuery();
        return $this->statement->num_rows;
    }
    
    /**
     * Fetch the result row as an associative array
     *
     * @return bool
     */
    public function queryResult()
    {
        $this->executeQuery();
        return $this->statement->fetch_assoc();
    }
    
    /**
     * Fetch the contents of the crawler.
     *
     * @return string
     */
    public function fetchData(): string
    {
        $autoIncrement = 1;
        $this->sql = "SELECT url, date_time FROM crawler_tbl ORDER BY id ASC";
        $result = $this->executeQuery();
        $data = "";
        while (null !== ($row = $result->fetch_assoc())) {
            $data .= '<tr class="high">
                        <td>'.$autoIncrement++.'</td>
                        <td><a href="'.$row['url'].'">'.$row['url'].'</a></td>
                        <td>'.$row['date_time'].'</td>
                      </tr>';
        }
        return $data;         
    }

}

