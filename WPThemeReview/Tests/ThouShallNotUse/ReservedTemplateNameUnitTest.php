<?php
/**
 * Unit test class for WPThemeReview Coding Standard.
 *
 * @package WPTRT\WPThemeReview
 * @link    https://github.com/WPTRT/WPThemeReview
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WPThemeReview\Tests\ThouShallNotUse;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the PrefixAllGlobals sniff.
 *
 * @package WPTRT\WPThemeReview
 *
 * @since 0.2.0
 */
class ReservedTemplateNameUnitTest extends AbstractSniffUnitTest {

	/**
	 * Get a list of all test files to check.
	 *
	 * @param string $testFileBase The base path that the unit tests files will have.
	 *
	 * @return string[]
	 */
	protected function getTestFiles( $testFileBase ) {
		$sep        = \DIRECTORY_SEPARATOR;
		$test_files = glob( dirname( $testFileBase ) . $sep . 'ReservedTemplateNameTests{' . $sep . ',' . $sep . '*' . $sep . '}*.inc', \GLOB_BRACE );

		if ( ! empty( $test_files ) ) {
			return $test_files;
		}
		return array( $testFileBase . '.inc' );
	}

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList( $testFile = '' ) {
		switch ( $testFile ) {
			case 'index-secondary.inc':
			case 'page-contact.inc':
				return array(
					2 => 1,
				);
			default:
				return array();
		}
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array();
	}
}
