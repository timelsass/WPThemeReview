<?php
/**
 * WPThemeReview Coding Standard.
 *
 * @package WPTRT\WPThemeReview
 * @link    https://github.com/WPTRT/WPThemeReview
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WPThemeReview\Sniffs\ThouShallNotUse;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Check if the template file is using reserved file name as a part of their name.
 *
 * @since 0.2.0
 */
class ReservedTemplateNameSniff implements Sniff {

	/**
	 * Error message template.
	 *
	 * @var string
	 */
	const ERROR_MSG = 'File template should not use a reserved name as a prefix. Found: "%s".';

	/**
	 * Regex to catch the template creation comment
	 *
	 * @var string
	 */
	const TEMPLATE_REGEX = '`\/\* Template Name:`i';

	/**
	 * Found prefix in a file
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * List of reserved template file names.
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
	 * @link https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/#content-slug-php
	 * @link https://wphierarchy.com/
	 * @link https://en.wikipedia.org/wiki/Media_type#Naming
	 *
	 * @since 0.2.0
	 *
	 * @var array
	 */
	protected $reserved_file_name_prefixes = [ 'index-', 'comments-', 'front-page-', 'home-', 'header-', 'singular-', 'single-', 'page-', 'category-', 'tag-', 'taxonomy-', 'author-', 'date-', 'archive-', 'search-', 'attachment-', 'image-', '404-' ];

	/**
	 * Strip quotes surrounding an arbitrary string.
	 *
	 * Intended for use with the contents of a T_CONSTANT_ENCAPSED_STRING / T_DOUBLE_QUOTED_STRING.
	 *
	 * Used from WordPressCS\WordPress\Sniff abstract class-
	 *
	 * @since 0.2.0
	 *
	 * @param string $string The raw string.
	 * @return string String without quotes around it.
	 */
	private function strip_quotes( $string ) {
		return preg_replace( '`^([\'"])(.*)\1$`Ds', '$2', $string );
	}

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$emptyTokens;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The PHP_CodeSniffer file where the
	 *                                               token was found.
	 * @param int                         $stackPtr  The position of the current token
	 *                                               in the stack.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		// Usage of `strip_quotes` is to ensure `stdin_path` passed by IDEs does not include quotes.
		$file = $this->strip_quotes( $phpcsFile->getFileName() );

		$fileName = basename( $file );

		if ( \defined( '\PHP_CODESNIFFER_IN_TESTS' ) ) {
			$fileName = str_replace( '.inc', '.php', $fileName );
		}

		// Check if the current file has a prefix in the reserved list.
		if ( ! $this->is_reserved_template_name_used( $fileName ) ) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		if ( preg_match( self::TEMPLATE_REGEX, $tokens[ $stackPtr ]['content'], $matches ) > 0 ) {
			$phpcsFile->addError(
				self::ERROR_MSG,
				$stackPtr,
				'Found',
				array( $this->prefix )
			);
		}
	}

	/**
	 * Checks if the given file is located in the $reserved_file_name_prefixes array.
	 *
	 * @param  string $file File name to check.
	 * @return boolean
	 */
	private function is_reserved_template_name_used( $file ) {
		foreach ( $this->reserved_file_name_prefixes as $prefix ) {
			if ( strpos( $file, $prefix ) !== false ) {
				$this->prefix = $prefix;
				return true;
			}
		}

		return false;
	}
}
