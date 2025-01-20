git clone https://github.com/nicolas-sanch/correction-2025

cd correction-2025
code .

cp .env.example .env

docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install
  

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate               
./vendor/bin/sail artisan migrate

./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
``