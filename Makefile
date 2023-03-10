.PHONY: image composer-update test release

IMAGE_NAME ?= codeclimate/codeclimate-phpmd
RELEASE_REGISTRY ?= codeclimate

ifndef RELEASE_TAG
override RELEASE_TAG = latest
endif

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

test:
	@$(MAKE) test-image > /dev/null
	docker run \
		--rm \
		--volume $(PWD)/tests:/usr/src/app/tests \
		$(IMAGE_NAME)-test \
		sh -c "vendor/bin/phpunit --bootstrap engine.php ./tests"

release:
	docker tag $(IMAGE_NAME) $(RELEASE_REGISTRY)/codeclimate-phpmd:$(RELEASE_TAG)
	docker push $(RELEASE_REGISTRY)/codeclimate-phpmd:$(RELEASE_TAG)
