<?php

function errorMsg($msg) {
	print "Error: ".$msg;
	exit;
}

// Get the URL to process
if (isset($_GET['url'])) {
	$url = $_GET['url'];
} else {
	errorMsg("No URL specified.");
}

// Limit the URL to this server only
$server = $_SERVER['HTTP_HOST'];
$url_hostname = parse_url($url,PHP_URL_HOST);
if ($server != $url_hostname) {
	errorMsg("URL is not local");
}

// Load the URL
$content = file_get_contents($url);

function unminifycss($content) {
	// get rid of comments
	$comments = array(
		"`^([\t\s]+)`ism"=>'',
		"`^\/\*(.+?)\*\/`ism"=>"",
		"`([\n\A;]+)\/\*(.+?)\*\/`ism"=>"$1",
		"`([\n\A;\s]+)//(.+?)[\n\r]`ism"=>"$1\n",
		"`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"\n"
	);
	$content = preg_replace(array_keys($comments),$comments,$content);
	// add a space before opening braces and tab after
	$content = preg_replace("/\s*{\s*/", " {\n\t", $content);
	// add a newline and a tab after each colon
	$content = preg_replace("/;\s*/", ";\n\t", $content);
	// add a newline after closing braces
	$content = preg_replace("/\s*}/", "\n}\n", $content);

	return $content;
}

header('Content-type: text/css');
print unminifycss($content);