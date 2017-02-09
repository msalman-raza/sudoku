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
    
    Last step is needed to run the migrations.
        
        
