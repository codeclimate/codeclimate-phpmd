.PHONY: image composer-update

IMAGE_NAME ?= codeclimate/codeclimate-phpmd

image:
	docker build --tag $(IMAGE_NAME) .

composer-update:
	docker run \
	  --rm \
	  --volume $(PWD)/composer.json:/usr/src/app/composer.json:ro \
	  $(IMAGE_NAME) \
	  sh -c 'php composer.phar update && cat composer.lock' > composer.lock
