Steps to install:

1. Clone the project
2. Run composer install into the project file
3. docker-compose up --build -d
4. docker-compose run 
      -v  path_to_the_file/stock.csv:/my-app/stock.csv 
       web php artisan csvimport:qubiz stock.csv
