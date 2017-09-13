<?php
/*
 * expandFns.php sets up most of the page expansion. HTTP handing takes place using an instance 
 * of the Snoopy class. Most of the page expansion depends on the classes in objects.php, 
 * particularly Template and Page.
 */

ini_set("user_agent", "Citation_bot; citations@tools.wmflabs.org");

if (!defined("html_output")) {
  define("html_output", -1);
}  

function quiet_echo($text, $alternate_text = '') {
  if (html_output >= 0)
    echo $text;
  else
    echo $alternate_text;
}

require_once("constants.php");
# Snoopy's ini files should be modified so the host name is en.wikipedia.org.
require_once("Snoopy.class.php");
require_once("DOItools.php");
require_once("Page.php");
require_once("Item.php");
require_once("Template.php");
require_once("Parameter.php");
require_once("Comment.php");
require_once("wikiFunctions.php");

//require_once(HOME . "credentials/mysql.login");
/* mysql.login is a php file containing:
  define('MYSQL_DBNAME', ...);
  define('MYSQL_SERVER', ...);
  define('MYSQL_PREFIX', ...);
  define('MYSQL_USERNAME', ...);
  define('MYSQL_PASSWORD', ...);
*/

require_once(HOME . "credentials/crossref.login");
/* crossref.login is a PHP file containing:
  <?php
  define('CROSSREFUSERNAME','martins@gmail.com');
  define('JSTORPASSWORD', ...);
  define('GLOBALPASSWORD', ...);
  define('JSTORUSERNAME', 'citation_bot');
  define('NYTUSERNAME', 'citation_bot');
*/

$crossRefId = CROSSREFUSERNAME;
mb_internal_encoding('UTF-8'); // Avoid ??s

//Optimisation
#ob_start(); //Faster, but output is saved until page finshed.
ini_set("memory_limit", "256M");

define("FAST_MODE", isset($_REQUEST["fast"]) ? $_REQUEST["fast"] : false);
define("SLOW_MODE", isset($_REQUEST["slow"]) ? $_REQUEST["slow"] : false);
if (isset($_REQUEST["crossrefonly"])) {
  $crossRefOnly = true;
} elseif (isset($_REQUEST["turbo"])) {
  $crossRefOnly = $_REQUEST["turbo"];
} else {
  $crossRefOnly = false;
}
$edit = isset($_REQUEST["edit"]) ? $_REQUEST["edit"] : null;

if ($edit || isset($_GET["doi"]) || isset($_GET["pmid"])) {
  $ON = true;
}

################ Functions ##############

function udbconnect($dbName = MYSQL_DBNAME, $server = MYSQL_SERVER) {
  // if the bot is trying to connect to the defunct toolserver
  if ($dbName == 'yarrow') {
    return ('\r\n # The maintainers have disabled database support.  This action will not be logged.');
  }

  // fix redundant error-reporting
  $errorlevel = ini_set('error_reporting','0');
  // connect
  $db = mysql_connect($server, MYSQL_USERNAME, MYSQL_PASSWORD) or die("\n!!! * Database server login failed.\n This is probably a temporary problem with the server and will hopefully be fixed soon.  The server returned: \"" . mysql_error() . "\"  \nError message generated by /res/mysql_connect.php\n");
  // select database
  if ($db && $server == "sql") {
     mysql_select_db(str_replace('-','_',MYSQL_PREFIX . $dbName)) or print "\nDatabase connection failed: " . mysql_error() . "";
  } else if ($db) {
     mysql_select_db($dbName) or die(mysql_error());
  } else {
    die ("\nNo DB selected!\n");
  }
  // restore error-reporting
  ini_set('error-reporting',$errorlevel);
  return ($db);
}

function countMainLinks($title) {
  // Counts the links to the mainpage
  global $bot;
  if (preg_match("/\w*:(.*)/", $title, $title))
    $title = $title[1]; //Gets {{PAGENAME}}
  $url = "https://en.wikipedia.org/w/api.php?action=query&bltitle=" . urlencode($title) . "&list=backlinks&bllimit=500&format=yaml";
  $bot->fetch($url);
  $page = $bot->results;
  if (preg_match("~\n\s*blcontinue~", $page))
    return 501;
  preg_match_all("~\n\s*pageid:~", $page, $matches);
  return count($matches[0]);
}

function logIn($username, $password) {
  global $bot; // Snoopy class loaded in DOItools.php
  // Set POST variables to retrieve a token
  $submit_vars["format"] = "json";
  $submit_vars["action"] = "login";
  $submit_vars["lgname"] = $username;
  $submit_vars["lgpassword"] = $password;
  // Submit POST variables and retrieve a token
  $bot->submit(api, $submit_vars);
  if (!$bot->results) {
    exit("\n Could not log in to Wikipedia servers.  Edits will not be committed.\n");
  }
  $first_response = json_decode($bot->results);
  $submit_vars["lgtoken"] = $first_response->login->token;
  // Resubmit with new request (which has token added to post vars)
  $bot->submit(api, $submit_vars);
  $login_result = json_decode($bot->results);
  if ($login_result->login->result == "Success") {
    quiet_echo("\n Using account " . htmlspecialchars($login_result->login->lgusername) . ".");
    // Add other cookies, which are necessary to remain logged in.
    $cookie_prefix = "enwiki";
    $bot->cookies[$cookie_prefix . "UserName"] = $login_result->login->lgusername;
    $bot->cookies[$cookie_prefix . "UserID"] = $login_result->login->lguserid;
    $bot->cookies[$cookie_prefix . "Token"] = $login_result->login->lgtoken;
    $bot->cookies[$cookie_prefix . "_session"] = $login_result->login->sessionid;
    return true;
  } else {
    exit("\n Could not log in to Wikipedia servers.  Edits will not be committed.\n");
    global $ON;
    $ON = false;
    return false;
  }
}

function inputValue($tag, $form) {
  //Gets the value of an input, if the input's in the right format.
  preg_match("~value=\"([^\"]*)\" name=\"$tag\"~", $form, $name);
  if ($name)
    return $name[1];
  preg_match("~name=\"$tag\" value=\"([^\"]*)\"~", $form, $name);
  if ($name)
    return $name[1];
  return false;
}

function format_title_text($title) {
  $title = html_entity_decode($title, null, "UTF-8");
  $title = str_replace(array("\r\n","\n\r","\r","\n"), ' ', $title); // Replace newlines with a single space
  $title = (mb_substr($title, -1) == ".")
            ? mb_substr($title, 0, -1)
            :(
              (mb_substr($title, -6) == "&nbsp;")
              ? mb_substr($title, 0, -6)
              : $title
            );
  $title = preg_replace('~[\*]$~', '', $title);
  $iIn = array("<i>","</i>", '<title>', '</title>',"From the Cover: ");
  $iOut = array("''","''",'','',"");
  $in = array("&lt;", "&gt;");
  $out = array("<", ">");
  $title = title_capitalization($title);
  
  return(sanitize_string(str_ireplace($iIn, $iOut, str_ireplace($in, $out, $title)))); // order IS important!
}

function remove_accents($input) {
  $search = explode(",", "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
  $replace = explode(",", "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
  return str_replace($search, $replace, $input);
}

function under_two_authors($text) {
  return !(strpos($text, ';') !== FALSE  //if there is a semicolon
          || substr_count($text, ',') > 1  //if there is more than one comma
          || substr_count($text, ',') < substr_count(trim($text), ' ')  //if the number of commas is less than the number of spaces in the trimmed string
          );
}

function title_case($text) {
  return mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
}

/** Returns a properly capitalised title.
 *      If sents is true (or there is an abundance of periods), it assumes it is dealing with a title made up of sentences, and allows the letter after any period to remain capitalized.
  *     If not, it will assume it is a journal abbreviation and won't capitalise after periods.
 */
function title_capitalization($in, $sents = TRUE, $could_be_italics = TRUE) {
  // Use 'straight quotes' per WP:MOS
  $new_case = straighten_quotes($in);
  
  if ( $new_case == mb_strtoupper($new_case) 
     && mb_strlen(str_replace(array("[", "]"), "", trim($in))) > 6
     ) {
    // ALL CAPS to Title Case
    $new_case = mb_convert_case($new_case, MB_CASE_TITLE, "UTF-8");
  }
  
  
  if ($could_be_italics) {
    // <em> tags often go missing around species names in CrossRef
    $new_case = preg_replace('~([a-z]+)([A-Z][a-z]+\b)~', "$1 ''$2''", $new_case);
  }
  
  if ($sents || (substr_count($in, '.') / strlen($in)) > .07) {
    // When there are lots of periods, then they probably mark abbrev.s, not sentance ends
    // We should therefore capitalize after each punctuation character.
    $new_case = preg_replace_callback("~[?.:!]\s+[a-z]~u" /* Capitalise after punctuation */,
      create_function('$matches','return mb_strtoupper($matches[0]);'),
      $new_case);
  }
  
  $new_case = preg_replace_callback(
    "~\w{2}'[A-Z]\b~u" /* Lowercase after apostrophes */, 
    create_function('$matches', 'return mb_strtolower($matches[0]);'),
    trim($in)
  );
  // Solitary 'a' should be lowercase
  $new_case = preg_replace("~(\w\s+)A(\s+\w)~u", "$1a$2", $new_case);
  $new_case = preg_replace_callback(
    "~(?:'')?(?P<taxon>\p{L}+\s+\p{L}+)(?:'')?\s+(?P<nova>(?:(?:gen\.? no?v?|sp\.? no?v?|no?v?\.? sp|no?v?\.? gen)\b[\.,\s]*)+)~ui" /* Species names to lowercase */,
    create_function('$matches', 'return "\'\'" . ucfirst(strtolower($matches[\'taxon\'])) . "\'\' " . strtolower($matches["nova"]);'),
    $new_case);
  
  // Capitalization exceptions, e.g. Elife -> eLife
  $new_case = str_replace(dontCap, unCapped, " " .  $new_case . " ");
  
  $new_case = substr($new_case, 1, strlen($new_case) - 2); // remove spaces, needed for matching in dontCap
  
  if (preg_match("~^(the|into|at?|of)\b~", $new_case)) {
    // If first word is a little word, it should still be capitalized
    $new_case = ucfirst($new_case);
  }
  return $new_case;
}

function tag($long = FALSE) {
  $dbg = array_reverse(debug_backtrace());
  array_pop($dbg);
  array_shift($dbg);
  foreach ($dbg as $d) {
    if ($long) {
      $output = '> ' . $d['function'];
    } else {
      $output = '> ' . substr(preg_replace('~_(\w)~', strtoupper("$1"), $d['function']), -7);
    }
  }
  echo ' [..' . htmlspecialchars($output) . ']';
}

function sanitize_string($str) {
  // ought only be applied to newly-found data.
  if (trim($str) == 'Science (New York, N.Y.)') return 'Science';
  $dirty = array ('[', ']', '|', '{', '}');
  $clean = array ('&#91;', '&#93;', '&#124;', '&#123;', '&#125;');
  return trim(str_replace($dirty, $clean, preg_replace('~[;.,]+$~', '', $str)));
}

function prior_parameters($par, $list=array()) {
  array_unshift($list, $par);
  if (preg_match('~(\D+)(\d+)~', $par, $match)) {
    switch ($match[1]) {
      case 'first': case 'initials': case 'forename':
        return array('last' . $match[2], 'surname' . $match[2]);
      case 'last': case 'surname': 
        return array('first' . ($match[2]-1), 'forename' . ($match[2]-1), 'initials' . ($match[2]-1));
      default: return array($match[1] . ($match[2]-1));
    }
  }
  switch ($par) {
    case 'title':       return prior_parameters('author', array_merge(array('author', 'authors', 'author1', 'first1', 'initials1'), $list) );
    case 'journal':       return prior_parameters('title', $list);
    case 'volume':       return prior_parameters('journal', $list);
    case 'issue': case 'number':       return prior_parameters('volume', $list);
    case 'page' : case 'pages':       return prior_parameters('issue', $list);

    case 'pmid':       return prior_parameters('doi', $list);
    case 'pmc':       return prior_parameters('pmid', $list);
    default: return $list;
  }
}
?>
