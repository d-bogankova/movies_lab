# Second Mastery Lab
App is working in two modes: Query Mode and List Mode. Query Mode is able in CLI.

# Getting Started

 **At the very start** you need to create Database and restore dump **db.sql** from the root directory. 
 **Then** you need to get an API-key from https://www.themoviedb.org/documentation
 **Then** open **config-dist.php**, rename it into **config.php** and specify your local setting.
 **Next** run application in Query Mode, after that movies will be imported and you can run List Mode to see movie data.
 
 # Query Mode
 Check it out in two varients: browser and CLI.
 In your **Browser** you need to input:
 ```sh
/index.php?action=query
```
It will add data into the Database and it takes some time. Be patient))

In **CLI** you just need to input:

 ```sh
$ php index.php
```

 # List Mode
To view list of movies - open this URL in you browser:
```sh
/index.php
```
## Versions
1.0 

## Author
Developed by D.Bogankova


=======
