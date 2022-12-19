.PHONY: qa
qa: cs phpstan rector-dry tests yaml-lint

.PHONY: coding-standards
cs: vendor
	.Build/bin/ecs check --fix

.PHONY: phpstan
phpstan: vendor
	.Build/bin/phpstan analyse

.PHONY: rector
rector: vendor
	.Build/bin/rector

.PHONY: rector-dry
rector-dry: vendor
	.Build/bin/rector --dry-run

.PHONY: tests
tests: vendor
	.Build/bin/phpunit --configuration=Tests/phpunit.xml.dist

vendor: composer.json composer.lock
	composer validate
	composer install
	composer normalize

.PHONY: yaml-lint
yaml-lint: vendor
	find -regex '.*\.ya?ml' ! -path "./.Build/*" -exec .Build/bin/yaml-lint -v {} \;

.PHONY: zip
zip:
	grep -Po "(?<='version' => ')([0-9]+\.[0-9]+\.[0-9]+)" ext_emconf.php | xargs -I {version} sh -c 'mkdir -p ../zip; git archive -v -o "../zip/$(shell basename $(CURDIR))_{version}.zip" v{version}'
