<?php 
/**
 * This file facilitates the sitemap-specific functionality of the application.
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
/**
 * Define the SitemapClass and set its functionality.
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
class SitemapClass
{
    private $_connection;

    /**
     * Establish connection to the database
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->_connection = new  ConnectionClass();
    }
    
    /**
     * Generate sitemap file 
     *
     * @return integer
     */
    public function generateSiteMap(): bool
    {
        $sitemapText = '<!DOCTYPE html PUBLIC 
            "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/
            REC-html40/loose.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" 
            xmlns:html="http://www.w3.org/TR/REC-html40" 
		    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">
		    <head>
		        <title>XML Sitemap</title>
		        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		        <meta name="robots" content="all, follow"/>
		        <meta name="viewport" content="width=device-width, initial-scale=1"/>
		        <style type="text/css">
		        	body {
		        			font-family:"Lucida Grande","Lucida Sans Unicode",Tahoma,Verdana;
		        			font-size:13px;
		        		}
		        		
		        		#intro {
		        			background-color:#CFEBF7;
		        			border:1px #2580B2 solid;
		        			padding:5px 13px 5px 13px;
		        			margin:10px;
		        		}
		        		
		        		#intro p {
		        			line-height:	16.8667px;
		        		}
		        		#intro strong {
		        			font-weight:normal;
		        		}
		        		
		        		td {
		        			font-size:11px;
		        		}
		        		
		        		th {
		        			text-align:left;
		        			padding-right:30px;
		        			font-size:11px;
		        		}
		        		
		        		tr.high {
		        			background-color:whitesmoke;
		        		}
		        		
		        		#footer {
		        			padding:2px;
		        			margin-top:10px;
		        			font-size:10pt;
		        			color:black;
		        		}
		        		
		        		#footer a {
		        			color:gray;
		        		}
		        		
		        		a {
		        			color:black;
		        		}
		    	</style>
			</head>
		    <body>
		        <h2 xmlns="">XML Sitemap Index</h2>
		        <div xmlns="" id="content">
		            <table cellpadding="5">
		                <tr style="border-bottom:1px black solid;">
		                    <th>Id</th> 
		                    <th>URL of sub-sitemap</th>
		                    <th>Last modified (GMT)</th>
		                </tr>';
        $sitemapText .= $this->_connection->fetchData(); 
        $sitemapText .= '</table>
					    </div>
					  </body>
					</html>';
        $sitemap = fopen("./../uploads/sitemap/sitemap.html", "w");
        if (false === fwrite($sitemap, $sitemapText)) {
            return false;
        }
        return true;
        fclose($sitemap);
    }
    
}
