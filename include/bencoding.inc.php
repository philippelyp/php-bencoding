<?php

/*
 *
 *  bencoding.inc.php
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





//
// bencoding
//

class bencoding
{
	//
	// Variables
	//

	private $error;
	private $string;
	private $position;

	//
	// __construct
	//

	function __construct()
	{
		$this->error = '';
	}

	//
	// __decode
	//

	private function __decode()
	{
		switch (self::read(1)) {

			case 'd':
				$this->position++;
				$array = array();
				while ('e' !== self::read(1)) {
					$array[self::parse_string()] = self::__decode();
				}
				$this->position++;
				return $array;

			case 'i':
				$this->position++;
				return self::parse_int();

			case 'l':
				$this->position++;
				$array = array();
				while ('e' !== self::read(1)) {
					$array[] = self::__decode();
				}
				$this->position++;
				return $array;

			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
			case '-':
				return self::parse_string();

			default:
				throw new exception('Unsupported type');
		}
	}

	//
	// decode
	//

	function decode($string)
	{
		$this->error = '';
		$this->string = $string;
		$this->position = 0;
		try {
			return self::__decode();
		} catch (exception $e) {
			$this->error = $e->getMessage();
			return NULL;
		}
	}

	//
	// __encode
	//

	private function __encode($value)
	{
		if (TRUE == is_array($value)) {
			if (array_values($value) !== $value) {
				ksort($value, SORT_STRING);
				$string = 'd';
				foreach ($value as $k => $v) {
					$string .= self::__encode(strval($k));
					$string .= self::__encode($v);
				}
				$string .= 'e';
				return $string;
			} else {
				$string = 'l';
				foreach ($value as $v) {
					$string .= self::__encode($v);
				}
				$string .= 'e';
				return $string;
			}
		}
		if (TRUE == is_int($value)) {
			return 'i' . $value . 'e';
		}
		if (TRUE == is_string($value)) {
			return strlen($value) . ':' . $value;
		}
		throw new exception('Unsupported type');
	}

	//
	// encode
	//

	function encode($value)
	{
		$this->error = '';
		try {
			return self::__encode($value);
		} catch (exception $e) {
			$this->error = $e->getMessage();
			return NULL;
		}
	}

	//
	// error
	//

	function error()
	{
		return $this->error;
	}

	//
	// parse_int
	//

	private function parse_int() {
		$delimiter_position = strpos($this->string, 'e', $this->position);
		if (FALSE === $delimiter_position) {
			throw new exception('End delimiter not found');
		}
		$int_length = $delimiter_position - $this->position;
		$int = self::read($int_length);
		if (FALSE == is_numeric($int)) {
			throw new exception('Integer is not numeric');
		}
		$this->position += $int_length + 1;
		return intval($int);
	}

	//
	// parse_string
	//

	private function parse_string()
	{
		$delimiter_position = strpos($this->string, ':', $this->position);
		if (FALSE === $delimiter_position) {
			throw new exception('End delimiter not found');
		}
		$int_length = $delimiter_position - $this->position;
		$int = self::read($int_length);
		if (FALSE == is_numeric($int)) {
			throw new exception('String lenght is not numeric');
		}
		$this->position += $int_length + 1;
		$int = intval($int);
		if ($int < 0) {
			throw new exception('String lenght is negative');
		}
		$string = self::read($int);
		$this->position += $int;
		return $string;
	}

	//
	// read
	//

	private function read($length)
	{
		$string = @substr($this->string, $this->position, $length);
		if ((FALSE === $string) || ($length != strlen($string))) {
			throw new exception('Truncated data');
		}
		return $string;
	}
}
