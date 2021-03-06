# This file is part of silex-base.
#
# Copyright (C)
#  2017 Alexander Haase <ahaase@mksec.de>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

# The test target will be used to run all tests on this project. It will link
# the individual test cases defined below.
.PHONY: test
test: test-php-cs-fixer test-phpunit

# Check the source code for following the code standard defined in the
# PHP-CS-Fixer configuration. This doesn't cover all conventions (e.g. line
# length), but most of them.
.PHONY: test-php-cs-fixer
test-php-cs-fixer:
	@vendor/bin/php-cs-fixer fix \
	    --config=.php_cs.dist    \
	    -v                       \
	    --dry-run                \
	    --stop-on-violation      \
	    --using-cache=no         \
	    src/ tests/ .php_cs.dist

# Run all test-cases with phpunit. Coverage data will be stored in the
# coverage.xml file.
test-phpunit:
	@vendor/bin/phpunit                   \
	    --configuration tests/phpunit.xml \
	    --coverage-clover=coverage.xml


# Generate the documentation for the whole project (all files in src sub-
# directory). The generated documentation (and cache files) will be stored in
# the doc subdirectory of the build path.
#
# NOTICE: Developers need to install phpdoc by the platform-dependent package
#         manager due dependency conflicts with Silex's dependencies.
.PHONY: doc
doc:
	phpdoc -t build/doc/ -d src/ -d tests/ -p
