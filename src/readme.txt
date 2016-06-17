This is a readme file for a Search Engine project. This project includes two parts, a java program and a website interface. Both parts are published on github.com
Github of the java program is https://github.com/tekmark/SearchEngine, and website interface is https://github.com/tekmark/SearchEngineWebInterface1
Sample website is www.codenotepad.com, alternavtive is chaohan.ddns.net. Note: the sample web server is based on a DDNS, therefore, if the website is not available please contact helloworld.c@icloud.com 

1. Overview
1.1 Hardware
	The sample server is build on a Raspberry Pi 3 which is a single board computer with an ARM cpu, a Linux system with kernel 4.1.20-v7+, and a Samsung EVO+ microsd card used as disk. 
	For more details about Raspberry Pi 3 specifications please check https://www.raspberrypi.org/.
1.2 WebServer
	Apache2 is run as a web server, tomcat is used as a java servlet server, and mongodb is used as a database server. Apache nutch is used to crawl and download webpages from Internet. A java program is used to index downloaded pages.
1.3 Client (Browser) and client-server communications
	Php is used as server script language. Pages are designed by using Javascript and HTML5, and Ajax and JQuery are also used.
1.4 Other
	Php/JavaBridge is used in this project, which is an implementation of a streaming, XML-based network protocol, which can be used to connect a php script with a Java virtual machine. For details please check http://php-java-bridge.sourceforge.net/pjb/
	By using Php/Java Bridge, the java program can be used as a standalone program as well as service. Users can use the jar file to search files via command line interface.	
2. Crawling Data
	Crawler used in this program is Apache Nutch 1.11 which is downloaded from http://nutch.apache.org/
	2.1 seed lists.
	Two related seed lists are used, one is urls related to universities, the other one is urls related to sports.
		2.1.1 edu_seed_list
		http://www.rutgers.edu/
		http://www.harvard.edu/
		http://www.princeton.edu/
		http://www.yale.edu/
		http://www.columbia.edu/
		http://www.caltech.edu/
		http://web.mit.edu/
		http://www.stanford.edu/	
		http://www.uchicago.edu/
		http://www.upenn.edu/	
		http://www.duke.edu/
		http://www.dartmouth.edu/
		http://www.northwestern.edu/
		http://www.jhu.edu/
		http://wustl.edu/
		http://www.brown.edu/
		http://www.rice.edu/
		http://www.vanderbilt.edu/
		http://nd.edu/
		http://www.emory.edu/home/
		http://www.asu.edu/ 
		http://www.washington.edu/
		2.1.2 sports_seed_list
		http://sports.yahoo.com/
		http://www.reuters.com/news/sports/
		http://www.msn.com/en-us/sports/
		http://www.bbc.com/sport/
		http://www.nbcsports.com/
		http://www.cbssports.com/
		http://www.sbnation.com/
		http://www.si.com/
		http://www.skysports.com/
		http://www.nba.com/
		http://www.nfl.com/
		https://www.nhl.com/
		http://www.mlssoccer.com/
		http://mlb.mlb.com/home
		http://espn.go.com/
		http://www.ufc.com/
		http://bleacherreport.com/
		http://www.uefa.com/
		http://www.fifa.com/
		http://www.premierleague.com/
	2.2 Crawldb and LinkDb
		The dowloaded data is stored as segments, and two databases are created by nutch. One is crawldb, the other is linkdb. 
		Statistics for CrawlDb: crawl/crawldb/
		TOTAL urls:	454000
		retry 0:	452444
		retry 1:	1556
		min score:	0.0
		avg score:	0.028281422
		max score:	4.978
		status 1 (db_unfetched):	383426
		status 2 (db_fetched):	57426
		status 3 (db_gone):	2524
		status 4 (db_redir_temp):	3129
		status 5 (db_redir_perm):	6808
		status 7 (db_duplicate):	687
		CrawlDb statistics: done
		On the raspberry pi server of this project, crawldb is 55M, linkdb is 26M, segments are 1.1G.
	2.3 link analysis
		There are 3 methods in nutch called webgraph, linkrank and scoreupdater. webgraph generates a web graph from segments, linkrank runs a link analysis program on the generated web graph, and scoreupdater updates the crawldb with linkrank scores.
	2.4 Dump files.
		A dump file of all segments need to be geneated as a input file for indexing. On the raspberry pi server, the size of the dump file is 1.2G.
		Dump files of inlinks, outlinks and scroes are used to get link analysis information of certain urls.
3. Indexing
	The java program is used to indexing.
	3.1 Dump files.
		In dump files introduced in section 2.4 are used to get an indexed database for searching.
	3.2 Before Indexing
		Before indexing segment dump file, a mongodb is needed to setup. Url scores are inserted to databases. 
	3.3 Tokenizer
		Tokenizer is used to convert a document to a linear bag of words. 
	3.4 Filters and Stemmer.
		Two filters are using in this project, which can be found in MyAnalyzer class. 
		1. convert all words to low cases. 
		2. remove all stop words in English, such as "a", "the", "of". 
		Porter Stemming Algorithm is used to transfer words to their root forms.
		For example, "Library" => "librari", "University" => "univers" 
	3.5 Lucene and Document. 
		Lucene package is used to indexing. A Document in luecene is a collection of Fields, and each field has semantics about how it is created and stored. 
		In this project, a Document includes 4 major fields, which are url, contents, title and socre of url.  
		Url, title are read from segment dump file directly, and contents are analyzed by Filters and Stemmer mentioned in Section 3.4. The scores are read from mongodb. 
		Indexing process can be found in DumpFileIndexer class.     
4. Searching
	The MySearcher class is used to query.
	4.1 Query
		Query String needs to be processed via the same steps as indexing contents of a document. 
		For example, If Query string is "university", the terms passed to Lucene Searcher should be "univers", because "university" is indexed in form of its root form "univers".
	4.2 Lucene Scoring
		Lucene scoring uses a combination of the Vector Space Model (VSM) of Information Retrieval and the Boolean model to determine how relevant a given Document is to a User's query. The algrithm used in Lucene Socring is TF-IDF. 
	4.3 Url Scoring
		The Url scoring is generated by nutch via building a web graph and evaluate the importance of linked urls in the web graph.
	4.4 Ranking Algorithm
		The algrithm used in the project is combining Lucene Score and Url Score. First, use Lucene scoring algrithm to get all documents sorted by lucene score of query terms. Second, for the a certain percentage or a centain lucene score range of all documents, rank them by using url scores. 
		Implementation of Ranking Algorithms can be found in MyRankingAlgorithm class.
5. User Interface
	5.1 Front-end 
		The website is a google-like website build by using HTML and Javascripts. 
	5.2 Front-end and Back-end Communications.
		Php is used as server script language. 
		JQuery and AJAX are used to communicate with php scripts, and A JSON format results object is sent to browser by server.  
		
6. Monitoring
	5.1 logging
		5.1.1 log4j
		log4j package is used by the java application for logging.
		5.1.2 log4php
		log4php is used for php logging.
		5.1.3 log.io
		A nodejs service called log.io is running on the server for viewing all realtime log files. 
	5.2 Nagios and Alerts. 
		Nagios is set up for monitoring all services running on the server. A STMP server is also set up to send alert emails to administritor.
		
7. Data Analysis
	5.1 Crawldb Analysis
		5.1.1 Subdomain Analysis
			For some domain such as rice.edu, subdomains were fetch quite well. A lot of subdomains of departments can be found, such as cs.rice.edu, ece.rice.edu, math.rice.edu, mech.rice.edu, business.rice.edu, etc.
		5.1.2 Static vs Dynamic pages
			50 files are sampled from dump file of segments. Most pages (90%) are static pages, urls with specific file path have higher possiblility to be static pages. And social media pages, for example pages from twitter.com, are likely to be dynamic pages.     
	5.2 Result Analysis
		For a single term search of university name, such as "yale", "rutgers", "mit", "rice", 20 out of 22 home pages of urls in edu_seed_list can be found in the first page. 
		For a 2-term search of university and department, such as "math" + "rice", "cs" + "rice", the home pages of the departments can also be found in the first result pages respectatively. But for some domains without department subdomains in database, results are considered not good enough. The reason is that the department home page is downloaded.
		For more terms search, most the results are considered to be also highly related. For example, when searching "president obama speech rutgers", top hits are all about the President Obama's speech at Rutgers.
		The sport category has similar results.  
	5.3 Comparison with Major Search Engine
		Google is considered to be the best search engine all over the world. It has an exetremely huge database, and most advanced ranking algorithms. Therefore, comparing to google's results is very useful for evaluation.
		By using google to search terms in Section 5.2, such as rice.edu in google, only first 2 hits can match google result. The reason of relatively poor results is becuase database is not huge enough.   
	5.4 Algorithm Analysis
		This combined ranking algrithm used in this project is simple. For single term, the url scores are dominating, and for query of multiple terms, lucene sorces are more important. This is reasonable because, when search a single word, such as "sports", there are tons of pages contains term "sports", results should be the most popular websites/domains. When query string has more terms, the higher lucene score, the more terms are found in a document which matches users' expectation.     
	5.5 Performance Analysis
		Normally, searching time, which includes searching, ranking, and io, is less than 1 sec. For example, for searching "yale cs", more than 4000 results are found. the searching time is 20 milliseconds,ranking time is 7 milliseconds, and total time is 983 millisecond, which shows that the limitaion is IO performance. Taking microsd card IO performance of raspberry pi into consideration, it is a relatively good performance, and it can be improved by disabling logging or using a fast drive. 
8. Future Works.
	A larger database will improve the searching results.
	
#Compilation and Installation Instruction
	Checkout Github
#Bugs and Issues
	Checkout Github
				
		
		
