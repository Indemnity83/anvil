VERSION ?= $(shell cat .version)

IMAGE_NAME = anvil
IMAGE_ORG = indemnity83

HTTP = 8080
MANAGE = 8888
SITES = `pwd`/build/sites
APPDATA = `pwd`/build/appdata

# HELP
# This will output the help for each task
# thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help build

help: ## This help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help


# DOCKER TASKS
# Build the container
build: ## Build the container
	docker build --build-arg VERSION=$(VERSION) -t $(IMAGE_NAME) .

build-nc: ## Build the container without caching
	docker build --build-arg VERSION=$(VERSION) --no-cache -t $(IMAGE_NAME) .

run: ## Run container on port configured in `.env`
	docker run -it --rm -v $(SITES):/home/anvil/ -v $(APPDATA):/anvil/storage -p ${HTTP}:8080 -p ${MANAGE}:8888 --name "$(IMAGE_NAME)" $(IMAGE_NAME)

up: build run ## Build the container then run it

fresh: build-nc run ## build without cache and run the container

clean: stop ## Stop any running containers and remove the image and build directories
	docker image ls $(IMAGE_NAME) -q | grep -q . && docker rmi $(IMAGE_NAME) || exit 0
	rm -r build

stop: ## Stop a running container
	docker ps -q --filter "name=$(IMAGE_NAME)" | grep -q . && docker stop "$(IMAGE_NAME)" && docker rm -f "$(IMAGE_NAME)" || exit 0

release: build-nc publish ## Make a release by building and publishing the `{version}` and `latest` tagged containers to Dockerhub

# Docker publish
publish: publish-latest publish-version ## Publish the `{version}` and `latest` tagged containers to Dockerhub'

publish-latest: tag-latest ## Publish the `latest` taged container to Dockerhub'
	@echo 'publish latest to $(IMAGE_ORG)'
	docker push $(IMAGE_ORG)/$(IMAGE_NAME):latest

publish-version: tag-version ## Publish the `{version}` taged container to Dockerhub'
	@echo 'publish $(VERSION) to $(IMAGE_ORG)'
	docker push $(IMAGE_ORG)/$(IMAGE_NAME):$(VERSION)

# Docker tagging
tag: tag-latest tag-version ## Generate container tags for the `{version}` ans `latest` tags

tag-latest: build ## Generate container `latest` tag
	@echo 'create tag latest'
	docker tag $(IMAGE_NAME) $(IMAGE_ORG)/$(IMAGE_NAME):latest


tag-version: build ## Generate container `{version}` tag
	@echo 'create tag $(VERSION)'
	docker tag $(IMAGE_NAME) $(IMAGE_ORG)/$(IMAGE_NAME):$(VERSION)

version: ## Output the current version
	@echo $(VERSION)
