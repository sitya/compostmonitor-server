# Komposztmonitor szerver

Az akalmazás hőmérsékleti adatok vizualizálására képes, az adatokat API hívásokon keresztül tölthetünk be az adatbázisba. A fejlesztést elsősorban a leendő komposztkazánunk viselkedésének hőmérsékleti monitorozása motiválta, de bármilyen hőmérsékleti értékek megjelenítésére használható.

Kiegészítő információk:
* A vizualizáció során téli időszámítást használunk - úgyis télen lényegesek az adatok.
* Óránként egy adatot rögzítünk, az egész órára történő kerekítést az egységesítés érdekében a szerver elvégzi
* A várható rengeteg adatra tekintettel grafikonhoz történő adatbetöltés aszinkron lesz a későbbiekben, ill. minél távolabbról nézzük majd a grafikont, annál ritkábbak lesznek a pontok (havi nézetben napi 1-2 pont elég)

A **kliens** innen tölthető le: https://github.com/sitya/compostmonitor-daemon.git

## Telepítés
Feltételezve, hogy van egy megfelelő Linux alapú szerverünk, Apache, MySQL, PHP környezettel, a telepítés menete a következő.

1. Ha nincs composer telepítve, akkor: `curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer`
2. `git clone https://github.com/sitya/compostmonitor-server.git`
3. `cd compostmonitor-server && composer install`
4. Ha még nincs kész az adatbázisunk: `app/console doctrine:database:create`
5. `app/console doctrine:schema:create`
6. Az apache konfigba tegyünk egy ilyet: `Alias compostmonitor-server /path/to/compostmonitor-server/web`

Ezek után a `http://akarmi.h/compostmonitor-server` oldalon böngészőből elérhető lesz a grafikon, melyen jó eséllyel nem lesz még adat, de a szerver más képes fogadni az adatgyüjtő kliensektől a hőmérsékleti adatokat. Amint az adatok érkeznek, a grafikonon egyből láthatók lesznek, további konfigurációt az alkalmazás nem igényel.

## Frissítés
Előfordul, hogy az alkalmazáson fejlesztésre kerül sor, sőt mostanában kifejezetten tervezek fejlesztéseket. Ekkor az alábbi lépésekkel lehet frissíteni a telepített verziót.

1. `cd compostmonitor-server`
2. `git pull`
3. `composer update`
4. `app/console cache:clear`


