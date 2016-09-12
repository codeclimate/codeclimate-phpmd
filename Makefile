.PHONY: image composer-update citest test

IMAGE_NAME ?= codeclimate/codeclimate-phpmd

image:
	docker build --tag $(IMAGE_NAME) .

composer-update:
	docker run \
	  --rm \
	  --volume $(PWD)/composer.json:/usr/src/app/composer.json:ro \
	  $(IMAGE_NAME) \
	  sh -c 'php composer.phar update && cat composer.lock' > composer.lock

citest:
	docker run --rm $(IMAGE_NAME) sh -c "cd /usr/src/app && vendor/bin/phpunit --bootstrap engine.php ./tests"

test: image
	docker run --rm $(IMAGE_NAME) sh -c "cd /usr/src/app && vendor/bin/phpunit --bootstrap engine.php ./tests"
