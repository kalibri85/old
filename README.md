
============

# Intro

- Lokalaus development'o aplinka (docker) (PHP 7.2, MySql DB, Nginx)

# Paleidimo instrukcija


### Reikės dokerio

Įsidiegti įrankį iš [čia](https://docs.docker.com/install/linux/docker-ce/ubuntu/). 
Iškart įdiegus reikia pasidaryti, kad `docker` būtų galima naudoti be root teisių, kaip tai padaryti rasite [čia](https://docs.docker.com/compose/install/).

Įsidiegti  `docker-compose` iš [čia](https://github.com/docker/compose/releases).

Taip pat reikia įsidiegti [Kitematic](https://github.com/docker/kitematic/releases).

#### Versijų reikalavimai
* docker: `18.x-ce`
* docker-compose: `1.20.1`


### Projekto paleidimas 
Parsisiunčiate šią repositoriją. 

Extractinat turinį į savo mėgstamą projektų direktoriją.

Einate į šią direktoriją su terminalu. 

* Susikuriate projekto viduje `.env` failą. Failą užpildote turiniu pateiktu iš `env.dist`:
  ```
  cp .env.dist .env
  ```

* Pasiruoškite infrastruktūrą:
  ```
  docker-compose up -d
  ```
#Projekto paleidimas (lokaliai)  
#### Pasruošiame frontend aplinką

* JavaScript/CSS įrankiams (**atsidaryti atskirame lange**)
```
docker-compose run --rm frontend.symfony
```
  * įsirašome JavaScript bilbiotekas
  ```
  npm install --no-save
  yarn run encore dev --watch
  ```

#### Pasruošiame backend aplinką


* PHP įrankiams (**atsidaryti atskirame lange**)
```
docker exec -it php.symfony bash
```
  * įsirašome PHP biliotekas:
  ```
  composer install
  bin/console cache:clear
  bin/console assets:install
  ```
### Duomenu bazes migracija ir užpildymas testiniais duomenimis
* Reikia leisti iš `docker exec -it php.symfony bash`
```
  bin/console doctrine:migrations:migrate
  bin/console doctrine:fixtures:load
```  
#### Pasižiūrime rezultatą

Atsidarome naršyklėje [127.0.0.1:8000](http://127.0.0.1:8000)

#Projekto paleidimas (produkcinėje)
#### Pasruošiame frontend aplinką

* JavaScript/CSS įrankiams (atsidaryti atskirame lange)
```
  docker-compose run --rm frontend.symfony

  npm install --no-save

  yarn run encore production
  ```
  
#### Pasruošiame backend aplinką

* PHP įrankiams (**atsidaryti atskirame lange, skiriasi nuo dev aplinkos**)
```
  docker exec -it prod.php.symfony bash

  composer install
 
  bin/console cache:clear
  bin/console assets:install
  ```
### Duomenu bazes migracija ir užpildymas testiniais duomenimis
* Reikia leisti iš `docker exec -it php.symfony bash`
```
  bin/console doctrine:migrations:migrate
  bin/console doctrine:fixtures:load
```  
  
#### Pasižiūrime rezultatą

Atsidarome naršyklėje [127.0.0.1:8888](http://127.0.0.1:8888)



