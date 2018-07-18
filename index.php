<html>
    <head>
        <title>cPanel Bulk Sub Domains Removal 1.1</title>
        <style>
            body
            {
                text-align: center;
                }
        </style>
        
    </head>
    <body>
        
        <h1>cPanel Bulk Sub Domains Deleting PHP Script (v1.1)</h1>
		<span>GitHub: <a href="https://github.com/MianShahzeb/cpanel-bulk-subdomain-delete" title="GitHub">MianShahzeb - Cpanel Bulk Domain Removal</a></span>
		<br><hr>
		<br>

<?php

####################################
# Name: cPanel Bulk Subdomains Removal 1.1
# Requirements to configure
# 1. cPanel Username
# 2. cPanel Password
# 3. cPanel Skin
# 4. Root Domain Name
# 5. Upload file.txt to public_html or any directory
#			Domains sample
#			sub-domain
#			subdomain2
#			subdomain5
#	Each line must contain on 1 sub domain in file.txt
# 6. Structure of $request
# 	6.1	Don't forget to sign in into your cpanel and check if you have the same prefix (cpsess16xxxxxx55)
#	6.2	=== https://[host here]:[port here]/cpsess/frontend/cpanel_skin/subdomain/dodeldomain.html?domain=[sub-domain here].[domain here]
#	6.3	Sample:
#		"/$cpess/frontend/$cpanel_skin/subdomain/dodeldomain.html?domain=$subd.$domain"
# 7. Port should be 2083 or 2082 (Normally it will 2083).
# 
####################################
#
# Contributor:   Mian Shahzeb
# Website:		 https://mianshahzeb.com/
# Github:		 https://github.com/MianShahzeb/cpanel-bulk-subdomain-delete
#		 
#####################################

// 1. cPanel Username
define('CPANELUSER','xxxxxxxxxxxxxx');

// 2. cPanel Password
define('CPANELPASS','xxxxxxxx');

// 5. File name of uploaded list of subdomains file.
define('INPUT_FILE','file.txt');

// 3. cPanel Skin
define('CPANEL_SKIN','paper_lantern');

// 4. Root Domain Name domain.tld
define('DOMAIN','example.com');




/////////////// END OF INITIAL SETTINGS ////////////////////////
////////////////////////////////////////////////////////////////

function getVar($name, $def = '') 
{
  if (isset($_REQUEST[$name]) && ($_REQUEST[$name] != ''))
    return $_REQUEST[$name];
  else
    return $def;
}

$cpaneluser=getVar('cpaneluser', CPANELUSER);
$cpanelpass=getVar('cpanelpass', CPANELPASS);
$cpanel_skin = getVar('cpanelskin', CPANEL_SKIN);


if (isset($_REQUEST["subdomain"])) {
  $doms = array( getVar('domain', DOMAIN) . ";" . $_REQUEST["subdomain"]);
  if (getVar('domain', DOMAIN) == '') die("You must specify domain name");
}
else {
// open file with domains list
  $doms = @file(INPUT_FILE);
  if (!$doms) {
// file does not exist, show input form
    echo "
<h3> We are unable to locate file.</h3><br><p>Make Sure You have uploaded file with same name that you provided on line 26</p>
";
    die();
  }
}

function subd($host,$port,$ownername,$passw,$request) {

  $errno = "";
  $errstr = "";
  $context = stream_context_create();

  $result = stream_context_set_option($context, 'ssl', 'verify_peer', false);
  $result = stream_context_set_option($context, 'ssl', 'verify_host', false);
  $result = stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
  $result = stream_context_set_option($context, 'ssl', 'CN_match', $host);

  $sock = stream_socket_client('ssl://' . $host . ':' . $port, $errno, $errstr, 60, STREAM_CLIENT_CONNECT, $context);
  if(!$sock) {
    print('Socket error or wrong cPanel username, password, cPanel skin and cpess.etc');
    exit();
  }

  $authstr = "$ownername:$passw";
  $pass = base64_encode($authstr);
  $in = "GET $request\r\n";
  $in .= "HTTP/1.0\r\n";
  $in .= "Host:$host\r\n";
  $in .= "Authorization: Basic $pass\r\n";
  $in .= "\r\n";

  fputs($sock, $in);
  while (!feof($sock)) {
    $result .= fgets ($sock,128);
  }
  fclose( $sock );

  return $result;
}

foreach($doms as $dom) {
  $lines = explode(';',$dom);
  if (count($lines) == 2) {
    
    $domain = trim($lines[0]);
    $subd = trim($lines[1]);
  }
  else {
// Sub Domains Passed
    $domain = getVar('domain', DOMAIN);
    $subd = trim($lines[0]);
  }
// 5. Replace cpess229xxxxxx99 with your own
// 6. Request Structure, You don't need to edit this.
  $request = "/cpsess22xxxxxx93/frontend/$cpanel_skin/subdomain/dodeldomain.html?domain=$subd.$domain";
// 7. Port
  $result = subd($domain,2083,$cpaneluser,$cpanelpass,$request);
  $show = strip_tags($result);
  echo "<p>
        Removing Sub domain: <span>.$subd.$domain</span>
        <noscript>$show</noscript><br></p>";
}

?>

</body>
</html>