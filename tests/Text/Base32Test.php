<?php

use Firehed\Text\Base32;

class Base32_Text_Tests extends PHPUnit_Framework_TestCase {

	function RFC4648Vectors() {
		return array(
			array('', ""),
			array('f', "MY======"),
			array('fo', "MZXQ===="),
			array('foo', "MZXW6==="),
			array('foob', "MZXW6YQ="),
			array('fooba', "MZXW6YTB"),
			array('foobar', 'MZXW6YTBOI======')
		);
	}


	/**
	 * @dataProvider RFC4648Vectors
	 */
	function testEncodingVectors($plain, $encoded) {
		$this->assertEquals($encoded, Base32::encode($plain));
	}

	/**
	 * @dataProvider RFC4648Vectors
	 */
	function testDecodingVectors($plain, $encoded) {
		$this->assertEquals($plain, Base32::decode($encoded));
	}

	function testNullEncoding() {
		$this->assertEquals("AA======", Base32::encode("\0"));
	}
	function testNullDecoding() {
		$this->assertEquals("\0", Base32::decode("AA======"));
	}
}
