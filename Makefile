bash:
	sudo docker-compose exec arquigrafia "/bin/bash"
mysql:
	sudo docker-compose exec mysql "/bin/bash"
install:
	sudo docker-compose build
	sudo docker-compose up -d
	sudo docker-compose exec arquigrafia sh -c "composer install"
	sudo chmod 777 -R ./storage
	sudo chmod 777 -R ./public/arquigrafia-images
	sudo chmod 777 -R ./public/arquigrafia-avatars
	sleep 10
	sudo docker-compose exec arquigrafia sh -c "npm install"
	sudo docker-compose exec arquigrafia sh -c "php artisan migrate"
	sudo docker-compose exec arquigrafia sh -c "npm run dev"
reset:
	sudo docker-compose up stop
	make install
up:
	sudo docker-compose up -d
dev:
	sudo docker-compose exec arquigrafia sh -c "npm run dev"
watch:
	sudo docker-compose exec arquigrafia sh -c "npm run watch"
