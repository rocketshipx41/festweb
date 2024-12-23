<?php

function smart_trim($src, $max_len)
{
    $result = $src;
    if (strlen($result) > $max_len) {
        $temp = wordwrap($result, $max_len, '|||');
        $temp = explode('|||', $temp);
        $result = array_shift($temp) . '...';
    }
    return restore_tags($result);
}

function restore_tags($input) {
    // Original PHP code by Chirp Internet: www.chirp.com.au
    // Please acknowledge use of this code by including this header.
    $opened = array();
    // loop through opened and closed tags in order
    if (preg_match_all("/<(\/?[a-z]+)>?/i", $input, $matches)) {
        foreach ($matches[1] as $tag) {
            if (preg_match("/^[a-z]+$/i", $tag, $regs)) { // a tag has been opened
                if (strtolower($regs[0]) != 'br') $opened[] = $regs[0];
            }
            elseif (preg_match("/^\/([a-z]+)$/i", $tag, $regs)) {
                // a tag has been closed
                $temp = array_keys($opened, $regs[1]);
                $temp = array_pop($temp);
                unset($opened[$temp]);
            }
        }
    } // close tags that are still open
    if ($opened) {
        $tagstoclose = array_reverse($opened);
        foreach($tagstoclose as $tag) $input .= "</$tag>";
    }
    return $input;
}
