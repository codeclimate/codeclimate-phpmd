.PHONY: image

IMAGE_NAME ?= codeclimate/codeclimate-phpmd

image:
	docker build --tag codeclimate/codeclimate-phpmd .
