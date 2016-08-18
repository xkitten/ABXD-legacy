<?php

//$isFirefox = FALSE;
//$isIE6 = FALSE;

//$lastKnownBrowser = $_SERVER['HTTP_USER_AGENT'];
$lastKnownBrowser = "Something";

//Opera/9.80 (iPhone; Opera Mini/5.0.0176/764; U; en) Presto/2.4.15

$knownBrowsers = array
(
	"MSIE" => "Internet Explorer",
	"Edge" => "Microsoft Edge",
	"Opera Mini" => "Opera Mini", //Opera/9.80 (J2ME/MIDP; Opera Mini/4.2.18887/764; U; nl) Presto/2.4.15
	"Nintendo Wii" => "Wii Internet Channel", //Opera/9.30 (Nintendo Wii; U; ; 3642; nl)
	"Nintendo DSi" => "Nintendo DSi Browser", //Opera/9.50 (Nintendo DSi; Opera/507; U; en-US)
	"Nitro" => "Nintendo DS Browser",
	"Nintendo 3DS" => "Nintendo 3DS",
	"Opera" => "Opera",
	"MozillaDeveloperPreview" => "Firefox dev",
	"PaleMoon" => "Pale Moon",
	"SeaMonkey" => "SeaMonkey",
	"Waterfox" => "Waterfox",
	"Firefox" => "Firefox",
	"Chrome" => "Chrome",
	"Safari" => "Safari",
	"Konqueror" => "Konqueror",
	"Mozilla" => "Mozilla",
	"Lynx" => "Lynx",
	"Nokia" => "Nokia mobile",
);

$knownOSes = array
(
	"HTC_" => "HTC mobile",
	"Windows 4.0" => "Windows 95",
	"Windows 4.1" => "Windows 98",
	"Windows 4.9" => "Windows ME",
	"Windows NT 5.0" => "Windows NT",
	"Windows NT 5.1" => "Windows XP",
	"Windows NT 5.2" => "Windows XP 64",
	"Windows NT 6.0" => "Windows Vista",
	"Windows NT 6.1" => "Windows 7",
	"Windows NT 6.2" => "Windows 8",
	"Windows NT 6.3" => "Windows 8.1",
	"Windows NT 10.0" => "Windows 10",
	"Windows Mobile" => "Windows Mobile",
	"Linux" => "Linux",
	"Mac OS X" => "Mac OS X %",
	"iPhone" => "iPhone",
	"iPad" => "iPad",
	"BlackBerry" => "BlackBerry",
	"Nintendo Wii" => "Nintendo Wii",
	"Nitro" => "Nintendo DS",
	"Android" => "Android",
);

$ua = $_SERVER['HTTP_USER_AGENT'];

foreach($knownBrowsers as $code => $name)
{
	if (strpos($ua, $code) !== FALSE)
	{
		//$version = substr($ua, strpos($ua, $code) + strlen($code), 6);
		//$version = preg_replace('/[^0-9,.]/','',$version);
		
		$versionStart = strpos($ua, $code) + strlen($code);
		$version = GetVersion($ua, $versionStart);

		//Opera Mini wasn't detected properly because of the Opera 10 hack.
		if (strpos($ua, "Opera/9.80") !== FALSE && $code != "Opera Mini" || $code == "Safari" && strpos($ua, "Version/") !== FALSE)
			$version = substr($ua, strpos($ua, "Version/") + 8);

		//$isFirefox = ($code == "Firefox");
		//$isIE6 = (strpos($ua, "MSIE 6.") !== FALSE);

		$lastKnownBrowser = $name." ".$version;
		break;
	}
}

$browserName = $name;
$browserVers = (float)$version;

$os = "";
foreach($knownOSes as $code => $name)
{
	if (strpos($ua, $code) !== FALSE)
	{
		$os = $name;
		
		if(strpos($name, "%") !== FALSE)
		{
			$versionStart = strpos($ua, $code) + strlen($code);
			$version = GetVersion($ua, $versionStart);
			$os = str_replace("%", $version, $os);
		}
	
		$lastKnownBrowser = format(__("{0} on {1}"), $lastKnownBrowser, $os);
		break;
	}
}

$lastKnownBrowser .= "<!-- ".htmlspecialchars($ua)." -->"; 

function GetVersion($ua, $versionStart)
{
	$numDots = 0;
	$version = "";
	for($i = $versionStart; $i < strlen($ua); $i++)
	{
		$ch = $ua[$i];
		if($ch == ';')
			break;
		if($ch == '_' && strpos($ua, "Mac OS X"))
			$ch = '.';
		if($ch == '.')
		{
			$numDots++;
			if($numDots == 3)
				break;
		}
		if(strpos("0123456789.", $ch) !== FALSE)
			$version .= $ch;
	}
	return $version;
}

?>
