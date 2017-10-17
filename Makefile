.PHONY: image composer-update test

IMAGE_NAME ?= codeclimate/codeclimate-phpmd

image:
	docker build --tag $(IMAGE_NAME) .

test-image: image
	docker build \
		--build-arg BASE_IMAGE=${IMAGE_NAME} \
		--tag $(IMAGE_NAME)-test \
		--file Dockerfile.test .

composer-update:
	docker run \
	  --rm \
	  --volume $(PWD)/composer.json:/usr/src/app/composer.json:ro \
	  --volume $(PWD)/composer.lock:/usr/src/app/composer.lock \
	  $(IMAGE_NAME) \
	  sh -c 'cd /usr/src/app && composer update'

citest: test

test:
	@$(MAKE) test-image > /dev/null
	docker run \
		--rm \
		--volume $(PWD)/tests:/usr/src/app/tests \
		$(IMAGE_NAME)-test \
		sh -c "vendor/bin/phpunit --bootstrap engine.php ./tests"
