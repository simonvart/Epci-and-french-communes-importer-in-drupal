
Need: having french communes and EPCI for a departement.

Communes are defined with name, departement, code insee, siren, code postal, epci, mayor.
EPCI are "Communautés de communes" and are defined with name, departement, siren, adress.

This modules creates two entities : communes and epci.
On the collection page for each, in the structure menu, you will find a button to import EPCI and communes.

Start by importing EPCI (admin/epci)

Then import communes (admin/commune). 
Note that if you didn't import EPCI beforehand, they will be imported anyway, so in practice you could import it all in one batch, because importing the communes will also update them with their associated EPCI if any.

The importation is limited about one departement, 01 (Ain), which is hard-coded because I needed this one, but it would be easy to make evolutions to manage a different one (and allow user to choose which one with a configuration), or several departements, or even all of them (it would probably require to extends the importer with a drush command).

Anyway, if you're french, and you need to import communes, or EPCI, or both, in your Drupal, this could be a head start.

You could also import the french translation which are now inside the translations folder from the `admin/config/regional/translate/import` admin page.

The module contains two files from which we import data:

INSEE EPCI: https://www.insee.fr/fr/information/2115000
DataNova: https://datanova.laposte.fr/explore/dataset/laposte_hexasmal/information/?disjunctive.code_commune_insee&disjunctive.nom_de_la_commune&disjunctive.code_postal&disjunctive.ligne_5

Feel free to fork, or ask for modifications if useful, and if not, well, I needed it, so I share anyway.

This is something of a beta version.

(Tested on a D9.2.0)
