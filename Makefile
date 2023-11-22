.PHONY: code-style
code-style: ## Executes php-cs-fixer with "check" option
	vendor/bin/php-cs-fixer check

.PHONY: fix-code-style
fix-code-style: ## Executes php-cs-fixer with "fix" option
	vendor/bin/php-cs-fixer fix
