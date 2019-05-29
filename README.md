Steps to install:

1. Clone the project
2. Run composer install into the project file
3. docker-compose up --build -d
4. docker-compose run 
      -v  path_to_the_file/stock.csv:/my-app/stock.csv 
       web php artisan csvimport:qubiz stock.csv


Display:

The number of lines that were processed 29
The number of lines that were successful 10
The number of lines that were not processed 1
The not processed lines : 
P0011,Misc Cables,error in export
