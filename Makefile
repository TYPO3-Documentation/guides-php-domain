.PHONY: qa
qa: fix-code-style test

.PHONY: code-style
code-style: ## Executes php-cs-fixer with "check" option
	vendor/bin/php-cs-fixer check

.PHONY: fix-code-style
fix-code-style: ## Executes php-cs-fixer with "fix" option
	vendor/bin/php-cs-fixer fix

.PHONY: test
test: ## Runs unit tests with phpunit
	vendor/bin/phpunit -c phpunit.xml.dist
