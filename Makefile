.PHONY: qa
qa: fix-code-style test

.PHONY: code-style
code-style: ## Executes php-cs-fixer with "check" option
	vendor/bin/php-cs-fixer check

.PHONY: fix-code-style
fix-code-style: ## Executes php-cs-fixer with "fix" option
	vendor/bin/php-cs-fixer fix

.PHONY: test-integration
test-integration: ## Runs integration tests with phpunit
	vendor/bin/phpunit --testsuite=integration

.PHONY: test-unit
test-unit: ## Runs unit tests with phpunit
	vendor/bin/phpunit --testsuite=unit

.PHONY: test
test: test-integration test-unit ## Runs all test suites with phpunit
