sudoku
======

I have used laravel\homestead as vagrant box , becuase its the first choice for symfony and also I think validators will have this box installed and will make installation time shorter

To Install application follow the steps

- Clone repository
- Run Composer
- Run Following commands to configure vagrant
    1. php vendor/bin/homestead make [mac & linux]
    2. vendor\\bin\\homestead make [windows]
    3. vagrant up
    4. vagrant ssh
    5. cd  /home/vagrant/sudoku
    6. php bin/console doctrine:schema:update --force 
    7. vendor/bin/phpunit to run the unit tests
    
    Last step is needed to run the migrations.
    
    
 -------------------------------
 
 API Calls
 -
 
 - http://homestead.app/sudoku [post] will make a game and will send game data with hash
 - http://homestead.app/sudoku [put] with x-www-form-urlencoded data [hash,value,row,colum] and it will update cell in database and sends the status and any conflicts found
 - http://homestead.app/sudoku/{hash} [Get] with hash in url to get complete data regarding game
 - http://homestead.app/sudoku [Delete] with x-www-form-urlencoded data [hash] and it will delete the game.
    
Description
-
As this was my first project in symfony so please relax if few things are not upto mark as per symfony.

Main logic resides in src/SudokuBundle

Folder Descriptions

- Entity , this folder has single game entity, require to persist game data
- exceptions, this folder has exceptions needed for game exceptions
- Librabry, this folder has all the business logic and is independed of symfony framework, this can be easily migrated to any framework, I have designed it as this is recommened practice to make business logic indepeneded of framework and database
- Repository, SudokuGame entity repository
- Resources, this has sample game and configurations
- Root, this folder has the Root class which will be used by controllers and by this SudokuBundle is indpended that it is used in API or Web calls


You will find the api calls in src/AppBundle/Controller/SudokuController


Tests are present in tests folder, currently Tests for root and api are not done, as I need to see how to mock database calls in symfony, will update it later on. Rest of all the business logic classes are fully covered in unit tests.



    
        
        
