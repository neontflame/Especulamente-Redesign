<?php
use Wikimedia\CSS\Parser\Parser;
use Wikimedia\CSS\Sanitizer\StylesheetSanitizer;

/** Parse a stylesheet from a string **/
function css_sanitario($cssText) {
	$parser = Parser::newFromString($cssText);
	$stylesheet = $parser->parseStylesheet();

	/** Report any parser errors **/

	foreach ( $parser->getParseErrors() as list( $code, $line, $pos ) ) {
		// $code is a string that should be suitable as a key for an i18n library.
		// See errors.md for details.
		$error = "css-parse-error-$code";
		echo "Parse error: $error at line $line character $pos\n";
	}

	/** Apply sanitization to the stylesheet **/

	// If you need to customize the defaults, copy the code of this method and
	// modify it.
	$sanitizer = StylesheetSanitizer::newDefault();
	$newStylesheet = $sanitizer->sanitize( $stylesheet );

	/** Report any sanitizer errors **/

	foreach ( $sanitizer->getSanitizationErrors() as list( $code, $line, $pos ) ) {
		// $code is a string that should be suitable as a key for an i18n library.
		// See errors.md for details.
		$error = "css-sanitization-error-$code";
		// echo "Sanitization error: $error at line $line character $pos\n";
	}

	/** Convert the sanitized stylesheet back to text **/

	return (string)$newStylesheet;
}