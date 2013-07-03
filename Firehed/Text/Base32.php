<?php
namespace Firehed\Text;

class base32 {
	const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
	
	static function encode($bin) {
		if (!$bin) return '';
		$out = '';
		do {
			$bytes = substr($bin, 0, 5);
			$ll = strlen($bytes);
			$bytes = str_pad($bytes,5,"\0", STR_PAD_RIGHT);
			$int = ord($bytes[0]) << 32
			     | ord($bytes[1]) << 24
			     | ord($bytes[2]) << 16
			     | ord($bytes[3]) << 8
			     | ord($bytes[4]) << 0
			     ;
			
			
			$toEnc[0] = ($int & 0xF800000000) >> 35;
			$toEnc[1] = ($int & 0x07C0000000) >> 30;
			$toEnc[2] = ($int & 0x003E000000) >> 25;
			$toEnc[3] = ($int & 0x0001F00000) >> 20;
			$toEnc[4] = ($int & 0x00000F8000) >> 15;
			$toEnc[5] = ($int & 0x0000007C00) >> 10;
			$toEnc[6] = ($int & 0x00000003E0) >> 5;
			$toEnc[7] = ($int & 0x000000001F) >> 0;
			foreach ($toEnc as $v) {
				$a = self::ALPHABET;
				$out .= $a[$v];
			}
			$bin = substr($bin, 5);

			
		} while ($bin);
		switch ($ll) {
			case 1:
			$out = substr_replace($out, '======', -6);
			break;
			case 2:
			$out = substr_replace($out, '====', -4);
			break;
			case 3:
			$out = substr_replace($out, '===', -3);
			break;
			case 4:
			$out = substr_replace($out, '=', -1);
			break;
			case 5: // ok
			break;
		}
		return $out;
	}
	static function decode($text) {
		if (!$text) return '';
		$lut = array_flip(str_split(self::ALPHABET));
		$lut['='] = 0;
		$out = '';
		do {
			$enc = substr($text, 0, 8);


			$int = $lut[$enc[0]] << 35
			     | $lut[$enc[1]] << 30
			     | $lut[$enc[2]] << 25
			     | $lut[$enc[3]] << 20
			     | $lut[$enc[4]] << 15
			     | $lut[$enc[5]] << 10
			     | $lut[$enc[6]] << 5
			     | $lut[$enc[7]] << 0
			     ;

			$out .= chr(($int & 0xFF00000000) >> 32)
			      . chr(($int & 0x00FF000000) >> 24)
			      . chr(($int & 0x0000FF0000) >> 16)
			      . chr(($int & 0x000000FF00) >> 8 )
			      . chr(($int & 0x00000000FF) >> 0 );

			$text = substr($text, 8);
		} while ($text);
		$pad = strpos($enc, '=');
		switch ($pad) {
			// XX======
			case 2: $out = substr($out, 0, -4); break;
			// XXXX====
			case 4: $out = substr($out, 0, -3); break;
			// XXXXX===
			case 5: $out = substr($out, 0, -2); break;
			// XXXXXXX=
			case 7: $out = substr($out, 0, -1); break;
			// XXXXXXXX
			case false: break;
		}
		return $out;
	}
}
