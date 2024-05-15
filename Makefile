.PHONY: qa
qa: fix-code-style test phpstan rector-dry

.PHONY: code-style
code-style: ## Executes php-cs-fixer with "check" option
	vendor/bin/php-cs-fixer check

.PHONY: fix-code-style
fix-code-style: ## Executes php-cs-fixer with "fix" option
	vendor/bin/php-cs-fixer fix

.PHONY: phpstan
phpstan: ## Execute phpstan
	vendor/bin/phpstan --configuration=phpstan.neon

.PHONY: phpstan-baseline
phpstan-baseline: ## Generates phpstan baseline
	vendor/bin/phpstan --configuration=phpstan.neon --generate-baseline

.PHONY: rector
rector: vendor
	vendor/bin/rector

.PHONY: rector-dry
rector-dry: vendor
	vendor/bin/rector --dry-run

.PHONY: test-integration
test-integration: ## Runs integration tests with phpunit
	vendor/bin/phpunit --testsuite=integration

.PHONY: test-unit
test-unit: ## Runs unit tests with phpunit
	vendor/bin/phpunit --testsuite=unit

.PHONY: test
test: fix-code-style phpstan test-integration test-unit ## Runs all test suites with phpunit
