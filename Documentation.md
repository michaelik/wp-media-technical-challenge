# Solution

## Introduction

Administrator wants to have visibility on how the sub-links of his website are indexed, thus he can manually search for ways to improve the SEO. This web application will allow administrators to have visibility on the SEO of his website so actions can be taken to improve the rankings. 

### Technology stack

Although I was given the choice between a native PHP application and a WordPress plugin, Native PHP was chosen as the prefered tool for this challenge, due to my incompetency in wordpress.

It will let me take advantage of its flexibility and ensure faster-processing speed.

Moreover, it has numerous packages that can be easily downloaded and used with composer.


#### General

- [SB Admin](https://startbootstrap.com/template/sb-admin) was used, because I think that it is not necessary to reinvent the wheel. I extracted the part needed for this project from the dist folder.

- Jquery was used 'cause it makes writing javascript easier and quicker.

- The project is secured and enables the admin to enter email and password for authorization.

- The project enables the admin to enter the host url, and trigger a craw.

- The project will store the data of the crawled pages in the database.

- The website runs on a PHP version equal to 7.0+

- Once the project is executed, the crawl is then launched automatically every hour.

- During each execution, the sitemap.html and the static page are reset.


#### Database

The database name is wp_crawler 

The data of the crawled pages are stored in the "crawler_tbl" table. The data collected for each page consist in a unique id, url and the date.

The admin login credential is stored in the "admin_tbl" table.

Admin webpage url is store in "host_tbl" table.


#### Third-party libraries

The project includes 1 externals libraries:

- [guzzlehttp](https://packagist.org/packages/guzzlehttp/guzzle): I included this library to facilitate seamless sending of HTTP requests and trivial to integrate with web services. It was not mandatory, but it helped me develop the project more easily.


### How it works

An ajax call itself cannot call class methods. It has no way of initiating the class and then calling the method. It can only 'cause the PHP file to run on the server via POST/GET requests on url. Thus I created an intermediary file(WP_crawler.php) to act as a go-between from the ajax to the class method. This approached to enforce separation of concerns. 

The admin has to be authenticated before making uses of the project's functionality. The email and password are:
`admin@gamil.com` and `password`

The password is already encrypted with a php in-built password hash and stored in the database. 

The dashboard page is displayed after the user logs in. It has a search box for the admin to enter the website host url and to manually start the crawl of the site. It currently accept only website with http and https, and has been tested with the following host: https://hashnode.com, http://google.com

If a crawl has already been performed, they is a second button to display the results of the last crawl as well as 2 buttons to open the sitemap.html file and the static file of the homepage.

#### The crawl

When the crawl is launched, a set of functions is executed. I tried to split each action into a function as much as possible.

###### Crawl button

The crawl is executed by clicking on the submit button "Crawl Now!"
A condition is added to verify the authenticity of the post request.

Once the verification is passed, 3 functions are called:
	
- The `tasks()` function which executes the crawl tasks (see description below).
- The `crawl()` function retrieves the web pages of the admin website, and stores them into an array.
- The `fetchData()` function which retrieves the results of the last crawl to display them on the dashboard page.

###### Crawl function

Function `$tasking->tasks()`

Jquery was used to pass the post request of the admin website url to the server php, and a condition was used to verify the post request.

Then the following tasks are executed:

- It deletes the record of the previous admin website url by truncating the "host_tbl" table.

- It deletes the records of the previous crawl by truncating the "crawler_tbl" table.

- It stores the current url of the admin website by inserting into the "host_tbl" table.

- It retrieved the admin website url from the "host_tbl" table, and crawled all the web pages. It also notify the admin if an error occurred.

- It deletes the records of the previous crawl by truncating the "crawler_tbl" table.

- It retrieves the current crawled web pages and stores them by inserting them into the "crawler_tbl" table.

- It detects if the previous sitemap.html exists or not, suppresses error reporting if it doesn't exist, otherwise deletes the  sitemap.

- It calls the function `generateSiteMap()` which creates the sitemap.html based on the crawl results.

### Setting up the cron

Window OS was used in building the project, task scheduler was used to perform the cron job on the file 'WP_crawler.php' located at the project directory -Navigate to the directory of the project c:/[upper directory/.../]/michael-Ikechukwu_PHP/AutoScriptRunner/ the two files in this directory are "script.bat" and "shellscript.vbs". These are the process to setup the cronjob, without with the cron wouldn't work. Open the first file(script.bat), they are two script listed. The first is the path to the php executable, and the second is the path to the php file the cron should execute. Modify it to match your directory path, save and close when done. Open the second file(shellscript.vbs), there are three scripts listed. The second line has a path to the "script.bat" file located within the project directory c:/[upper directory/.../]/michael-Ikechukwu_PHP/AutoScriptRunner/script.bat -modify it to match your directory path. 

Below are process to run task scheduler:

- Open task scheduler by pressing the key win + s, type the name. when the task scheduler window popped up. click action -> create task.

- When the create task window pops up. there are five tabs namely General|Triggers|Actions|Conditions|Settings.

- In the General tab, Enter your preferred name for the task, and navigate to the triggers tab.

- In the Trigger tab, click on the new button. When a new trigger window pops up, tick the box "repeat task every" and select 1hour from the drop down beside. click ok to close the window.

- Navigate to the Actions tab, click on the new button. when a new Action window popped up. Browse the path of shellscript.vbs in your pc in ‘Program/script’. In my case it's 'C:\laragon\www\WP\AutoScriptRunner\shellscript.vbs'.
skip the rest, and press ok to create the action.

- Skip the rest of the tabs in the task window and press the ok button to create the task.
