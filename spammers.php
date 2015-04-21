<?php
// Developer : Ekrem KAYA
// Website   : http://e-piksel.com
// Extension : http://weblenti.com/opencart-spam-referrer-blocker-s1-p83
// GitHub    : https://github.com/e-piksel/spam-referrer-blocker

// UTF8 Library
if (is_file('library/utf8.php')) {
	require_once('library/utf8.php');
}

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$ref_url = getRefUrl($_SERVER['HTTP_REFERER']);
		
	foreach (getBlacklist() as $spammer) {
		$spammer_url = getRefUrl($spammer);

		if ($ref_url == $spammer_url) {
			$spammerBye = 'Location: ' . $spammer;
			header($spammerBye);

			exit();
		}
	}
}

function getBlacklist() {
	$blacklist_data = array();

	if (is_file('blacklist.txt')) {
		$blacklist = 'blacklist.txt';
	} else {
		$blacklist = 'https://raw.githubusercontent.com/e-piksel/spam-referrer-blocker/master/blacklist.txt';
	}

	$spammers = file($blacklist, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	foreach ($spammers as $spammer) {
		$blacklist_data[] = utf8_strtolower(trim($spammer));
	}

	return $blacklist_data;
}

function getRefUrl($url) {
	$hostname = @parse_url($url, PHP_URL_HOST);

	// If the URL can't be parsed, use the original URL
	// Change to "return false" if you don't want that
	if (!$hostname) {
		$hostname = $url;
	}

	// The "www." prefix isn't really needed if you're just using
	// this to display the domain to the user
	if (utf8_substr($hostname, 0, 4) == "www.") {
		$hostname = utf8_substr($hostname, 4);
	}

	// You might also want to limit the length if screen space is limited
	if (utf8_strlen($hostname) > 50) {
		$hostname = utf8_substr($hostname, 0, 47) . '...';
	}

	return utf8_strtolower($hostname);
}