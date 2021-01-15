<?php

if (!defined("IN_MYBB")) {
    die("Nice try but wrong place, smartass. Be a good boy and use navigation.");
}
$plugins->add_hook('parse_message_end', 'relugc_commit');

function relugc_info()
{
    return array(
        'name' => 'relugc',
        'description' => 'Add \'User Generated Content\' (ugc) attribute to all links posted by user.',
        'website' => 'https://demonate.club/thread-relugc',
        'author' => 'effone',
        'authorsite' => 'https://eff.one',
        'version' => '1.0.0',
        'compatibility' => '18*',
        'codename' => 'relugc',
    );
}

function relugc_activate(){}
function relugc_deactivate(){}

function relugc_commit(&$msg)
{
    $msg = preg_replace_callback(
        "#(<a[^>]+?)>#is", function ($match) {
			global $mybb;
			if(strpos($match[1], $mybb->settings['bburl']) === false)
			{
				if(strpos($match[1], 'rel=') !== false){
					if(strpos($match[1], 'ugc') !== false){
						return $match[0];
					} else {
						return preg_replace('#(rel=[\'"]{1}(.?)+?)([\'"]{1})#i', '$1 ugc$3', $match[0]);
					}
				}
				return $match[1] . ' rel="ugc">';
			}
			return $match[0];
        },
        $msg
    );
}