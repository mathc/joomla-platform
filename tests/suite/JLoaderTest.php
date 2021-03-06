<?php
/**
 * @package     Joomla.UnitTest
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

include_once JPATH_PLATFORM . '/loader.php';

/**
 * Test class for JLoader.
 * Generated by PHPUnit on 2009-10-16 at 23:32:06.
 *
 * @package	Joomla.UnitTest
 */
class JLoaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JLoader is an abstract class of static functions and variables, so will test without instantiation
	 */
	protected $object;
	/**
	 * @var bogusPath is the path to the bogus object for loader testing
	 */
	protected $bogusPath;
	/**
	 * @var bogusFullPath is the full path (including filename) to the bogus object
	 */
	protected $bogusFullPath;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->bogusPath = JPATH_TESTS.'/objects';
		$this->bogusFullPath = JPATH_TESTS.'/objects/bogusload.php';
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 *	The test cases for importing classes
	 *
	 * @return array
	 */
	function casesImport()
	{
		return array(
			'factory' => array(
				'joomla.factory',
				null,
				null,
				true,
				'factory should load properly',
				true,
			),
			'jfactory' => array(
				'joomla.jfactory',
				null,
				null,
				false,
				'JFactory does not exist so should not load properly',
				true,
			),
			'fred.factory' => array(
				'fred.factory',
				null,
				null,
				false,
				'fred.factory does not exist',
				true,
			),
			'bogus' => array(
				'bogusload',
				JPATH_TESTS.'/objects',
				'',
				true,
				'bogusload.php should load properly',
				false,
			),
			'helper' => array(
				'joomla.user.helper',
				null,
				'',
				true,
				'userhelper should load properly',
				true,
			),
		);
	}

	/**
	 * The success of this test depends on some files being in the file system to be imported. If the FS changes, this test may need revisited.
	 *
	 * @param   string	$filePath		Path to object
	 * @param   string	$base			Path to location of object
	 * @param   string	$libraries		Which libraries to use
	 * @param   bool	$expect			Result of import (True = success)
	 * @param   string	$message		Failure message
	 * @param   bool	$useDefaults	Use the default function arguments
	 *
	 * @group   JLoader
	 * @covers  JLoader::import
	 *
	 * @return void
	 * @dataProvider casesImport
	 */
	public function testImport( $filePath, $base, $libraries, $expect, $message, $useDefaults )
	{
		if ($useDefaults) {
			$output = JLoader::import($filePath);
		} else {
			$output = JLoader::import($filePath, $base, $libraries);
		}

		$this->assertThat(
			$output,
			$this->equalTo($expect),
			$message
		);
	}

	/**
	 * The success of this test depends on the bogusload object being present in the
	 * unittest/objects tree
	 *
	 * @group   JLoader
	 * @covers  JLoader::register
	 * @return void
	 */
	public function testRegistersAGoodClass()
	{
		JLoader::register('BogusLoad', $this->bogusFullPath);

		$this->assertTrue(in_array($this->bogusFullPath, JLoader::getClassList()));
	}

	/**
	 * This test should try and fail to register a non-existent class
	 *
	 * @group   JLoader
	 * @covers  JLoader::register
	 * @return void
	 */
	public function testFailsToRegisterABadClass()
	{
		JLoader::register("fred", "fred.php");

		$this->assertFalse(in_array('fred.php', JLoader::getClassList()));
	}

	/**
	 *	The test cases for jimport-ing classes
	 *
	 * @return array
	 */
	function casesJimport()
	{
		return array(
			'fred.factory' => array(
				'fred.factory',
				false,
				'fred.factory does not exist',
			),
			'helper' => array(
				'joomla.installer.helper',
				true,
				'installerhelper should load properly',
			),
		);
	}

	/**
	 * This tests the convenience function jimport.
	 *
	 * @param   string	$object		Name of object to be imported
	 * @param   bool	$expect		Expected result
	 * @param   string	$message	Failure message to be displayed
	 *
	 * @return void
	 * @dataProvider casesJimport
	 * @group   JLoader
	 */
	public function testJimport( $object, $expect, $message )
	{
		$this->assertThat(
			$expect,
			$this->equalTo(jimport($object)),
			$message
		);
	}
}
