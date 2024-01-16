<div style="display: flex; flex-direction: column; justify-content: space-between; height: 100vh; padding: 20px;">

# <div align="center"><u> Analyse statistique </u></div>

![Logo de l'IUT](img/LogoIUT.jpeg)

<div style="display: flex; justify-content: space-between; align-items: flex-end;">
  <p></p>
  <blockquote style="text-align: right;">
    Prénoms & Noms des étudiants :<br>
    Rayan BEN TANFOUS<br>
    Armand CLOUZEAU<br>
    Sarah BADER<br>
    Ismail AKBOULATOV<br>
    Aymeric PESENTI
  </blockquote>
</div>
</div>
<div style="page-break-after: always;"></div>

1. [Présentation de Shiny](#presentation-de-shiny)
    - [Introduction à Shiny : Une Approche Interactive en R](#introduction-shiny)
    - [Conception de l'Interface Utilisateur (UI) : Personnaliser l'Expérience avec des Widgets](#interface-utilisateur)
    - [Le Serveur Shiny : Exécution de Code R pour des Sorties Dynamiques](#serveur-shiny)
    - [Réactivité en Temps Réel avec Shiny : Interaction Instantanée Utilisateur-Serveur](#reactivite)

2. [Création d’une application Shiny simpliste](#creation-application)

3. [Utilisation d’un jeu de données fictifs dans le cadre de notre application web](#jeu-de-donnees)
    - [Création du jeux de données](#creation-jeux-donnees)
    - [Programmation d’une application Shiny pour utilisant notre jeu de données](#programmation)

4. [Conclusion](#conclusion)

## <a id="presentation-de-shiny">1. Présentation de Shiny</a>

### <a id="introduction-shiny">Introduction à Shiny : Une Approche Interactive en R</a>

Shiny est un package R qui permet de créer des applications web interactives. Il offre une structure claire pour séparer les aspects de l'interface utilisateur (UI) et du serveur de l'application (Source: [lien](https://perso.ens-lyon.fr/lise.vaudor/Tuto_Shiny/tuto_Shiny_fr_Part1.html)).

### <a id="interface-utilisateur">Conception de l'Interface Utilisateur (UI) : Personnaliser l'Expérience avec des Widgets</a>

L'interface utilisateur (UI) constitue la face visible de l'application, déterminant ce que les utilisateurs voient et avec quoi ils interagissent. À l'aide de widgets tels que des boutons, des champs de texte, et des listes déroulantes, les utilisateurs peuvent personnaliser leur expérience, influençant ainsi les données présentées à l'écran (Source: [lien](https://epirhandbook.com/fr/shiny.html)).

### <a id="serveur-shiny">Le Serveur Shiny : Exécution de Code R pour des Sorties Dynamiques</a>

Le serveur est l'endroit où le code R est exécuté pour produire les outputs, tels que les graphiques, les tables, les traitements, etc. Il reçoit les inputs dynamiques de l'interface utilisateur, le serveur réagit instantanément en calculant à nouveau les sorties, créant ainsi une expérience interactive et réactive (Source: [lien](https://perso.ens-lyon.fr/lise.vaudor/Tuto_Shiny/tuto_Shiny_fr_Part1.html)).

### <a id="reactivite">Réactivité en Temps Réel avec Shiny : Interaction Instantanée Utilisateur-Serveur</a>

Shiny se distingue par son concept de réactivité, offrant une expérience interactive en temps réel pour les utilisateurs. Cette caractéristique essentielle permet une communication fluide entre l'interface utilisateur (UI) et le serveur, garantissant que toute modification apportée par l'utilisateur se traduit instantanément par des mises à jour dynamiques dans les sorties générées par le serveur. Lorsque les utilisateurs interagissent avec les widgets de l'interface utilisateur, tels que des boutons, des sélecteurs, ou des champs de texte, Shiny capture ces changements en temps réel. Ces interactions déclenchent alors l'exécution du code R du serveur associé. Le serveur produit ensuite de nouvelles sorties, que ce soit des graphiques actualisés, des tableaux modifiés, ou d'autres résultats en fonction des actions de l'utilisateur.

## <a id="creation-application"> 2. Création d’une application Shiny simpliste</a>

Pour créer une application Shiny, vous devez d'abord organiser vos fichiers de manière appropriée. Habituellement, vous aurez `ui.R`, `server.R`, et éventuellement `global.R` à la racine de votre répertoire d'application. Vous pouvez également avoir des sous-dossiers pour les données, les scripts, etc. (Source: [lien](https://perso.ens-lyon.fr/lise.vaudor/Tuto_Shiny/tuto_Shiny_fr_Part1.html)).

Voici un exemple de code pour une application Shiny simple :

```R
# Définition de l'interface utilisateur avec un titre, une barre latérale et un panneau principal
ui <- fluidPage(
 titlePanel("Mon application Shiny"),
 sidebarLayout(
   # Barre latérale avec un slider pour régler le nombre d'observations
   sidebarPanel(
     sliderInput("obs", "Nombre d'observations :", min = 1, max = 1000, value = 500)
   ),
   # Panneau principal avec un espace pour afficher le graphique
   mainPanel(
     plotOutput("distPlot")
   )
 )
)

# Définition du serveur qui réagit aux inputs de l'utilisateur et produit un graphique
server <- function(input, output) {
 output$distPlot <- renderPlot({
   # Utilisation de la fonction hist pour créer un histogramme avec des données aléatoires
   hist(rnorm(input$obs), col = 'darkgray', border = 'white')
 })
}

# Création de l'application Shiny en combinant l'interface utilisateur et le serveur
shinyApp(ui = ui, server = server)
```
Dans cet exemple, l'interface utilisateur contient un slider qui permet à l'utilisateur de choisir le nombre d'observations à générer pour le histogramme. Le serveur génère l'histogramme en utilisant le nombre d'observations choisi par l'utilisateur. L'histogramme est ensuite affiché dans le panneau principal de l'interface utilisateur (Source: [lien](https://www.christophenicault.com/fr/post/introduction_to_shiny/)).

## <a id="jeu-de-donnees">3. Utilisation d’un jeu de données fictifs dans le cadre de notre application web</a>

### <a id="creation-jeux-donnees">Création du jeux de données</a>

Nous avons créé un jeu de donnée dans un fichier csv qui comporte diverses colonnes : `id_ticket`, `login`, `sujet`, `description`, `priorité`, `date_creation`, `heure`, `status`, `date_fermeture`.

L'id ticket nous permet d’identifier chaque ticket entre eux. Le login nous permet de savoir qui à créé chaque ticket. Le sujet nous permet de savoir sur quoi porte le ticket. La description nous permet de connaître plus en détail le problème de la personne qui a ouvert le ticket. La priorité nous permet de connaître l’urgence du souci que rencontre l’utilisateur. La date de création nous permet de connaitre la date de création du ticket. L’heure nous permet de connaître l’heure de création du ticket. Le statut nous permet de savoir où en est le ticket. Deux statuts sont possibles : en cours de traitement et fermé. La date de fermeture nous permet de savoir quand le ticket à été clôturé par un de nos administrateur ou technicien.

Voici un extrait de notre jeux de donnée “tickets.csv” :
id_ticket,login,sujet,description,priorité,date_creation,heure,status,date_fermeture
1,user123,Demande d'assistance,Problème avec l'imprimante,haute,2023-12-15,09:30,en cours de traitement,None
2,user456,Problème de connexion,Impossible de se connecter au réseau,moyenne,2023-12-14,11:45,fermé,2023-12-14
3,user789,Besoin d'aide pour un logiciel,Erreur inconnue lors de l'utilisation,haute,2023-12-13,14:20,en cours de traitement,None
4,user789,Demande de formation,Formation sur le nouveau logiciel requis,faible,2023-12-12,10:00,en cours de traitement,None
5,user456,Problème de messagerie,Emails non reçus dans la boîte de réception,moyenne,2023-12-11,16:55,fermé,2023-12-11
6,user123,Demande de matériel,Nouveau bureau requis pour le département,haute,2023-12-10,13:10,en cours de traitement,None
7,user789,Problème avec le système,Écrans figés après la mise à jour,haute,2023-12-09,08:45,en cours de traitement,None
8,user456,Besoin d'assistance téléphonique,Configuration du téléphone professionnel,faible,2023-12-08,09:20,en cours de traitement,None
9,user123,Problème d'accès aux fichiers,Impossible d'ouvrir certains documents,haute,2023-12-07,14:35,en cours de traitement,None
10,user789,Demande de dépannage,Dysfonctionnement du système de ventilation,haute,2023-12-06,12:00,fermé,2023-12-06


### <a id="programmation">Programmation d’une application Shiny pour utilisant notre jeu de données</a>

Après avoir réfléchi avec toutes l’équipe, nous avons décidé d’utiliser Shiny afin de présenter nos données de manière captivante, comme si nous étions en face d'un investisseur. Notre objectif primordial était de mettre en lumière des données pertinentes susceptibles d'apporter une réelle valeur ajoutée à notre application.

Nous souhaitions dans un premier temps voir choisir ce que nous voulions voir grâce à une liste déroulante proposant 6 choix complètement différents :

1. Le premier bouton est la priorité des tickets. À sa création, une priorité est attribuée par l’utilisateur pour représenter l’urgence de son problème, allant de faible à haute. Si l’utilisateur s'intéresse aux priorités des tickets, il pourrait voir le pourcentage de chaque priorité ainsi que le nombre de tickets créés avec chaque priorité.
    ![image montrant la priorité des tickets](img/priorites_tickets.png)

2. Le second bouton est la date de création des tickets. Cette partie est certainement la plus représentative. Elle nous permet très clairement de s’apercevoir des mois ou des années durant lesquels de nombreux tickets sont ouverts. Nous avons jugé pertinent d’afficher le nombre moyen de tickets créés par mois et par année.
   ![image montrant la date de création des tickets](img/creation_tickets.png)

3. Le troisième bouton concerne l'heure de création des tickets, offrant la possibilité de visualiser la création des tickets en fonction du temps. Cette fonctionnalité permet de détecter des tendances et des variations, contribuant ainsi à une analyse approfondie des moments cruciaux liés à la demande d'assistance.
   ![image montrant les heures de créations des tickets](img/heure_creation_tickets.png)

4. Le quatrième bouton est la répartition des statuts des tickets. Nos tickets ont deux statuts possibles : en cours de traitement ou fermé. Nous avons jugé intéressant de réaliser un camembert pour représenter les statuts des tickets. De ce fait, l’utilisateur pourra visualiser le pourcentage de chaque statut ainsi que le nombre de tickets qui ont chaque statut.
   ![image montrant les status des tickets](img/status_tickets.png)

5. Pour le cinquième bouton, nous avons affiché la durée de résolution des tickets. Nous avons réparti les tickets résolus en trois catégories : moins d’une semaine, entre une semaine et un mois, plus d’un mois. De ce fait, nous pouvons très clairement voir le temps de résolution de nos tickets. Le camembert nous permet de voir un pourcentage et un texte sur la gauche permet de voir le nombre exact de tickets dans chaque catégorie.
   ![image montrant la durée de résolution des tickets](img/duree_resolution_tickets.png)

6. Le sixième et dernier bouton donne accès à l'ensemble du jeu de données CSV. Il présente l'ensemble des tickets répertoriés dans un tableau complet, offrant une vision exhaustive de l'ensemble des informations disponibles.
   ![image montrant les valeurs des tickets](img/valeurs_tickets.png)

## <a id="conclusion">4. Conclusion</a>

En conclusion, notre exploration approfondie de Shiny pour la création d'applications web interactives a révélé la puissance et la flexibilité de cet outil dans le domaine de la probabilité et des statistiques. La séparation claire entre l'interface utilisateur (UI) et le serveur, associée à la réactivité en temps réel, a permis de concevoir une application captivante pour la présentation de données complexes.

La réalisation de notre application Shiny, centrée sur un jeu de données fictifs représentatif de situations variées, a abouti à un outil offrant des fonctionnalités diversifiées. Les six boutons, chacun dédié à un aspect spécifique des tickets, permettent une exploration détaillée et personnalisée des données. De la priorité des tickets à la répartition des statuts, en passant par la durée de résolution, chaque fonctionnalité vise à fournir des informations claires et significatives.

En somme, Shiny s'est révélé être un outil essentiel pour notre équipe, offrant une plateforme robuste pour présenter des données de manière engageante et accessible. Son utilisation a considérablement enrichi notre capacité à communiquer efficacement des informations statistiques complexes, renforçant ainsi notre prise de décision et notre compréhension des tendances au sein de notre jeu de données.
