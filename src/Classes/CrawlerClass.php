<?php 
namespace Classes;
/*Composer's autoload*/
include_once './../vendor/autoload.php';


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class CrawlerClass
{
	public function crawl (string $url = ''): array
	{
		$urls = [];

	    if($url != ''){
	        $baseUrl = $url;
	        $prefix = 'https';
	        if (strpos($baseUrl, 'https://') === false)
	        {
	            $prefix = 'http';
	        }
	        try {
	            $client = new Client();
	            $response = $client->request('GET', $url);
	            $html = $response->getBody();     
	        } catch (ConnectException $e) {
	            return array();
	        }
	        //Getting the exact url without http or https
	        $url = str_replace('http://www.', '',$url);
	        $url = str_replace('https://www.', '',$url);
	        $url = str_replace('http://', '',$url);
	        $url = str_replace('https://', '',$url);
	        //Parsing the url for getting host information
	        $parse = parse_url('https://'.$url);
	        //Parsing the html of the base url
	        $dom = new \DOMDocument();
	        @$dom->loadHTML($html);
	        // grab all the on the page
	        $xpath = new \DOMXPath($dom);
	        //finding the a tag
	        $hrefs = $xpath->evaluate("/html/body//a");
	        //Loop to display all the links
	        $length = $hrefs->length;
	        //Converting URLs to add the www prefix to host to a common array
	        $baseUrl = str_replace('http://'.$parse['host'], 'http://www.'.$parse['host'],$baseUrl);
	        $baseUrl = str_replace('https://'.$parse['host'], 'https://www.'.$parse['host'],$baseUrl);
	        $urls = [$baseUrl];
	        $allUrls = [$baseUrl];
	        for ($i = 0; $i < $length; $i++) 
	        {
	            $href = $hrefs->item($i);
	            $url = $href->getAttribute('href');
	            $url = str_replace('http://'.$parse['host'], 'http://www.'.$parse['host'],$url);
	            $url = str_replace('https://'.$parse['host'], 'https://www.'.$parse['host'],$url);
	            //Replacing the / at the end of any url if present
	            if (substr($url, -1, 1) == '/')
	            {
	                $url = substr_replace($url, "", -1);
	            }
	            array_push($allUrls, $url);
	        }

	        //Looping for filtering the URLs into a distinct array
	        foreach($allUrls as $url)
	        {
	            //Limiting the number of urls on the site
	            if (count($urls) >= 300)
	            {
	                break;
	            }
	            //Filter the null links and images
	            if (strpos($url, '#') === false)
	            {
	                //Filtering the links with host
	                if (strpos($url, 'https://'.$parse['host']) !== false || strpos($url, 'https://www.'.$parse['host']) !== false)
	                {
	                    //Replacing the / at the end of any url if present
	                    if (substr($url, -1, 1) == '/')
	                    {
	                        $url = substr_replace($url, "", -1);
	                    }
	                    //Checking if the link is already preset in the final array
	                    $urlSuffix = str_replace('http://www.', '',$url);
	                    $urlSuffix = str_replace('https://www.', '',$urlSuffix);
	                    $urlSuffix = str_replace('http://', '',$urlSuffix);
	                    $urlSuffix = str_replace('https://', '',$urlSuffix);

	                    if ($urlSuffix != $parse['host'])
	                    {
	                        array_push($urls, $url);
	                    }
	                }
	                //Filtering the links without host
	                if (strpos($url, $parse['host']) === false)
	                {
	                    if (substr($url, 0, 1) == '/')
	                    {
	                        //Replacing the / at the end of any url if present
	                        if (substr($url, -1, 1) == '/')
	                        {
	                            $url = substr_replace($url, "", -1);
	                        }
	                        $newUrl = 'http://www.'.$parse['host'].$url;
	                        $secondUrl = 'https://www.'.$parse['host'].$url;
	                        if ($url != $parse['host'])
	                        {
	                            //Checking if the link is already preset in the final array and the common array
	                            if (!in_array($secondUrl, $urls) && !in_array($secondUrl, $allUrls) && !in_array($newUrl, $allUrls)) 
	                            {
	                                if ($prefix == 'https')
	                                {
	                                    $newUrl = $secondUrl;
	                                }
	                                array_push($urls, $newUrl);
	                            }
	                        }
	                    }
	                }
	            }
	        }
	    }
	    return $urls;
	}
}
