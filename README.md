<h1 align="center">Restat API</h1>

## About Restat

It is a software that is aimed at helping FET better manage recording of marks and grade generation of results during CAs and Exams.

## Prerequisites to running this project.
    
- Have PHP 8.1 or higher installed.
- Have Composer installed on your pc [Composer](https://getcomposer.org/).
- Have Xampp installed on your pc [Xampp](https://www.apachefriends.org/).

## Steps to follow after cloning
    
- Create a .env file at the root of the project.
- Copy everything in the .env.example file and paste it in the newly created .env file.
- Go to the .env file, line 14 and put in the databse name.
- Open a terminal at the root of the project and run the command 'composer install'.
- Still on your terminal, run the command 'php artisan key:generate'.
- To migrate the database, run the command 'php artisan migrate".
- To seed the database, run the command 'php artisan db:seed.
- To view the documentation, run the command 'php artisan serve' then go to http://127.0.0.1:8000/docs.


## Contributing

- DO NOT PUSH TO MAIN!!!
- If you wish to contribute, clone the repository, check out to the 'dev' branch, and create your branch from the 'dev' branch.
- All pull requests should be made to the 'dev' branch.
# lewa
