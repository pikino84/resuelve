#  Calculadora de salarios para Resuelve

Esta es una aplicación web la cual calcula los salarios de uno o varios equipos de fútbol de acuerdo a condiciones previamente establecidas.

##  Condiciones

El sueldo de los jugadores se compone de dos partes un **sueldo fijo** y **un bono variable**, la suma de estas dos partes es el sueldo de un jugador. El bono variable se compone de dos partes **meta de goles individual** y **meta de goles por equipo** cada una tiene un peso de 50%.

La aplicación web  deberá hacer el cálculo del sueldo de los jugadores del equipo Resuelve FC.

**¿Cómo se calculan los alcances de meta y bonos?**

La meta individual de goles por jugador depende del nivel que tenga asignado:
| Nivel |Goles/mes|
| ------------- |:-------------:| 
|A |5|
|B |10|
|C |15|
|Cuauh |20|

Ejemplo:
Durante el mes los jugadores Juan, Pedro, Martín y Luis tuvieron los sisguentes resultados:

| Jugador | Nivel |Goles anotados en el mes/mínimo requerido|
| ------------- |:-------------:| :-----------: |
|Juan | A |6/5|
|Pedro | B |7/10|
|Martín |C |16/15|
|Luis | Cuauh |19/20|
|  | | |
| total |  |48/50|

Por lo tanto el bono por equipo tendra un alcance del 96%,
Luis tendra un alcance individual del 95%, para un alcance total del 95.5%.
El suelo fijo de Luis es de 50,000.00 y su bono será de 10,000.00 por lo que su sueldo final será $59,550.00

## Ingreso de información

La aplicación deberá recibir como entrada un JSON. Además de calcular el sueldo de los jugadores del Resuelve FC, la aplicación puede calcular el sueldo de los jugadores de otros equipos con distintos mínimos por nivel.

Posibles estructuras permitidas JSON:

**Primera estructura JSON**
```json
[  
   {  
      "nombre":"Juan Perez",
      "nivel":"C",
      "goles":10,
      "sueldo":50000,
      "bono":25000,
      "sueldo_completo":null,
      "equipo":"rojo"
   },
   {  
      "nombre":"EL Cuauh",
      "nivel":"Cuauh",
      "goles":30,
      "sueldo":100000,
      "bono":30000,
      "sueldo_completo":null,
      "equipo":"azul"
   }
]
```
**Segunda estructura JSON**
```json
[
   {  
      "nombre":"El Rulo",
      "goles_minimos":10,
      "goles":9,
      "sueldo":30000,
      "bono":15000,
      "sueldo_completo": 14250,
      "equipo":"rojo"
   }
]
```
## Probar la aplicación
1. Montar un servidor web en tu equipo usando alguno de los siguientes servicios:
* [XAMPP](https://www.apachefriends.org/es/index.html) para Window, Mac o Linux
* [WAMPP](https://sourceforge.net/projects/wampserver/) para Windows
* [MAMPP](https://www.mamp.info/en/mac/) para Mac
* [LAMP](https://www.digitalocean.com/community/tutorials/como-instalar-linux-apache-mysql-php-lamp-en-ubuntu-16-04-es) para Linux
2. Clonar el proyecto en la carpeta root de tu servidor local; por default es htdocs.
3. Abrir la aplicación e inicia el servicio Apache Web Server.
3. Abrir un navegador web con la siguiente dirección **http://localhost/resuelve/**
## Requerimientos de código
El código debe funcionar en una instalación PHP 7.2 en vanilla. Tenga esto en cuenta antes de hacer un pull request.
## Para probar en línea
[Demo](https://valentinaplace.com/)
