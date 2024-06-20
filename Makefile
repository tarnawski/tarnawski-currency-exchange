.DEFAULT_GOAL := default

CWD:=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

# PHP CodeSniffer. Detects coding standard violations. (https://github.com/squizlabs/PHP_CodeSniffer)
phpcs:
	php vendor/bin/phpcs --standard="PSR12" -n src/ tests/

# PHP CodeSniffer. Automatically correct coding standard violations. (https://github.com/squizlabs/PHP_CodeSniffer)
phpcbf:
	php vendor/bin/phpcbf --standard="PSR12" -n src/ tests/

# PHPStan. Finding errors in your code without actually running it. (https://github.com/phpstan/phpstan)
phpstan:
	php vendor/bin/phpstan analyse

# PHP Mess Detector. Look for several potential problems within source. (https://phpmd.org)
phpmd:
	php vendor/bin/phpmd src/ text codesize,unusedcode

# PHPUnit. Running unit tests. (https://github.com/sebastianbergmann/phpunit)
phpunit:
	php vendor/bin/phpunit -c phpunit.xml.dist --testsuite=unit

behat:
	php vendor/bin/behat

default: phpcs phpstan phpmd phpunit
