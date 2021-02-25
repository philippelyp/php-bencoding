# PHP implementation of bencoding
Copyright 2021 Philippe Paquet

---

### Description

Bencoding is an encoding algorithm used by the peer-to-peer file sharing system BitTorrent for storing and transmitting loosely structured data. It specify and organize data in a terse format and supports the following types:
* byte strings
* dictionaries
* integers
* lists

Bencoding is part of the [BitTorrent specification](https://www.bittorrent.org/beps/bep_0003.html).

---

### Usage

Include `bencoding.inc.php` which specify the `bencoding` class:

```php
require_once('include/bencoding.inc.php');
```

Create a bencoding object:

```php
$bencoding = new bencoding();
```

After creation, the bencoding object offer 3 simple functions:

```php
// Return $string decoded or NULL if there was an error
$value = $bencoding->decode($string);
```

```php
// Return $value encoded or NULL if there was an error
$string = $bencoding->encode($value);
```

```php
// Return the last error as a string
$error = $bencoding->error();
```

---

### Example

```php
require_once('include/bencoding.inc.php');

$value = array(	'key_1' => 'string',
		'key_2' => 123456,
		'key_3' => array('item_1', 'item_2', 'item_3')
		);

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
```

Will display the following:

```
string(65) "d5:key_16:string5:key_2i123456e5:key_3l6:item_16:item_26:item_3ee"

array(3) {
  ["key_1"]=>
  string(6) "string"
  ["key_2"]=>
  int(123456)
  ["key_3"]=>
  array(3) {
    [0]=>
    string(6) "item_1"
    [1]=>
    string(6) "item_2"
    [2]=>
    string(6) "item_3"
  }
}
```

---

### Contributing

Bug reports and suggestions for improvements are most welcome.

---

### Contact

If you have any questions, comments or suggestions, do not hesitate to contact me at philippe@paquet.email
