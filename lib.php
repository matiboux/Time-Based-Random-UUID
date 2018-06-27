<?php
/**
 * Alternative UUID Generation Script
 * 
 * This is an alternative version of a randomly generated UUID, using both the timestamp and pseudo-random bytes.
 * Maybe I'm wrong about all that but I thought mixing the UUID v1 and v4 methods would make the generated UUID more secure, as it ensures a zero-chance of collision of a single computer and a -really- low chance of collision on multiple computers (because of both probability and time).
 * The UUID contains 62 randomly generated bits, so there is 2^62 different outcomes for that part of the generated UUID, which means you'd start geeting colisions after about 2^31 generations. That being said, that would only happen if you were able to generate that many UUIDs within 100 nanosecond to 1 microsecond (because the gettimeofday() function used here returns a microsecond-precise timestamp).
 * 
 * This generated UUID format is {oooooooo-oooo-Mooo-Nxxx-xxxxxxxxxxxx}, it concatenates:
 * - o: The current timestamp (60 bits) (time_low, time_mid, time_high)
 * - M: The version (4 bits)
 * - N: The variant (2 bits)
 * - x: Pseudo-random values (62 bits)
 * 
 * Based on:
 * - Code from an UUID v1 Generation script. https://github.com/fredriklindberg/class.uuid.php/blob/c1de11110970c6df4f5d7743a11727851c7e5b5a/class.uuid.php#L220
 * - Code from an UUID v4 Generation script. https://stackoverflow.com/a/15875555/5255556
 * 
 * @author Matiboux <matiboux@gmail.com>
 * @link https://github.com/matiboux/Time-Based-Random-UUID
 * @version 1.0
 * @return string Returns the generated UUID.
 */
function uuid($tp = null) {
	if(!empty($tp)) {
		if(is_array($tp)) $time = ($tp['sec'] * 10000000) + ($tp['usec'] * 10);
		else if(is_numeric($tp)) $time = (int) ($tp * 10000000);
		else return false;
	} else $time = (int) (gettimeofday(true) * 10000000);
	$time += 0x01B21DD213814000;
	
	$arr = str_split(dechex($time & 0xffffffff), 4); // time_low (32 bits)
	$high = intval($time / 0xffffffff);
	array_push($arr, dechex($high & 0xffff)); // time_mid (16 bits)
	array_push($arr, dechex(0x4000 | (($high >> 16) & 0x0fff))); // Version (4 bits) + time_high (12 bits)
	
	// Variant (2 bits) + Cryptographically Secure Pseudo-Random Bytes (62 bits)
	if(function_exists('random_bytes')) $random = random_bytes(8);
	else $random = openssl_random_pseudo_bytes(8);
	$random[0] = chr(ord($random[0]) & 0x3f | 0x80); // Apply variant: Set the two first bits of the random set to 10.
	
	$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', array_merge($arr, str_split(bin2hex($random), 4)));
	return strlen($uuid) == 36 ? $uuid : false;
}
?>