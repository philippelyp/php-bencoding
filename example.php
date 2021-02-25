<?php

/*
 *
 *  example.php
 *
 *  Copyright 2021 Philippe Paquet
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

require_once('include/bencoding.inc.php');

$value = array('key_1' => 'string', 'key_2' => 123456, 'key_3' => array('item_1', 'item_2', 'item_3'));

// Create the bencoding object
$bencoding = new bencoding();

// Encode $value
$string = $bencoding->encode($value);
if (NULL === $string) {
	echo $bencoding->error() . PHP_EOL;
} else {
	var_dump($string);
}

echo PHP_EOL;

// Decode $string
$value = $bencoding->decode($string);
if (NULL === $value) {
	echo $bencoding->error() . PHP_EOL;
} else {
	var_dump($value);
}
