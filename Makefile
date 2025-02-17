#!/bin/sh -l

# Define variables
IMAGE_DEV=izdrail/publicsos.ro:dev
IMAGE_PROD=izdrail/publicsos.ro:latest
DOCKERFILE=Dockerfile
DOCKER_COMPOSE_FILE=docker-compose.yaml
DOCKER_COMPOSE_FILE_PROD=docker-compose.yaml

# Targets
build-dev:

	docker buildx build \
		--platform linux/amd64 \
		-t $(IMAGE_DEV) \
		--progress=plain \
		-f $(DOCKERFILE) \
		.  # <-- Build Context Docker file is located at root

build-prod:
	docker image rm -f $(IMAGE_PROD) || true
	docker buildx build \
		--platform linux/amd64 \
		-t $(IMAGE_PROD) \
		--no-cache \
		--progress=plain \
		--build-arg CACHEBUST=$$(date +%s) \
		-f $(DOCKERFILE) \
		.  # <-- Build Context Docker file is located at root

dev:
	docker-compose -f $(DOCKER_COMPOSE_FILE) up --remove-orphans

prod:
	docker-compose -f $(DOCKER_COMPOSE_FILE_PROD) up --remove-orphans

down:
	docker-compose -f $(DOCKER_COMPOSE_FILE) down

ssh:
	docker exec -it publicsos.ro /bin/bash

publish-dev:
	docker push $(IMAGE_DEV)


publish-prod:
	docker push $(IMAGE_PROD)


# Additional functionality
test:
	docker exec publicsos.ro php artisan test

migrate:
	docker exec publicsos.ro php artisan migrate --force

seed:
	docker exec publicsos.ro php artisan db:seed --force

clean-queue:
	docker exec publicsos.ro php artisan horizon:clear

lint:
	docker exec publicsos.ro ./vendor/bin/phpcs --standard=PSR12 app/

fix-lint:
	docker exec publicsos.ro ./vendor/bin/phpcbf --standard=PSR12 app/

prune:
	docker system prune -f --volumes

logs:
	docker logs -f furaciuni.ro

restart:
	docker-compose -f $(DOCKER_COMPOSE_FILE) down
	docker-compose -f $(DOCKER_COMPOSE_FILE) up --remove-orphans -d

# Cleanup target
clean:
	-docker-compose -f $(DOCKER_COMPOSE_FILE) down --rmi all --volumes --remove-orphans
	-docker system prune -f --volumes
