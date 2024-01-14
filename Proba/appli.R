# Charger les bibliothèques
library(shiny)
library(dplyr)
library(ggplot2)
library(lubridate)
library(plotly)

# Charger les données
tickets <- read.csv("tickets.csv")

# Formater l'heure pour ne prendre que l'heure (sans les minutes)
tickets$heure <- format(as.POSIXct(paste(tickets$date_creation, tickets$heure)), "%H:00")

# Ajouter une colonne pour le mois (année-mois) pour faciliter l'agrégation
tickets$mois <- format(as.Date(tickets$date_creation), "%m")

# Ajouter une colonne pour l'année
tickets$annee <- format(as.Date(tickets$date_creation), "%y")

# Ajouter une colonne duree_resolution initialisée à NA
tickets$duree_resolution <- NA

# Convertir les colonnes de date au bon format
tryCatch({
  tickets$date_creation <- as.Date(tickets$date_creation, format = "%Y-%m-%d")
  tickets$date_fermeture <- as.Date(tickets$date_fermeture, format = "%Y-%m-%d")
}, error = function(e) {
  cat("Erreur lors de la conversion des dates :", conditionMessage(e), "\n")
})

# Interface utilisateur Shiny
ui <- fluidPage(
  div(id = "titre",  # Ajout de l'identifiant ici
      titlePanel("Analyse des Tickets")
  ),
  includeCSS("www/style.css"),
  sidebarLayout(
    sidebarPanel(id="sidebar",
      div(id = "boutton",
            selectInput("variable", "Choisir une variable :", choices = c("priorité", "date_creation", "heure", "status", "duree_resolution", "afficher_valeurs")),
      ),conditionalPanel(
        condition = "input.variable == 'date_creation'",
        radioButtons("temps", "Choisir une période :", choices = c("Mois" = "mois", "Année" = "annee"))
      ),
      hr(),
      # Valeur à afficher
      htmlOutput("valeur_affiche")
    ),
    mainPanel(
      tableOutput("table"),
      plotlyOutput("histogram")
    )
  )
)

# Serveur Shinys
server <- function(input, output) {
  output$table <- renderTable({
    # Afficher le tableau des tickets si l'option choisie est "afficher_valeurs"
    if (input$variable == "afficher_valeurs") {
      #afficher une valeur null pour que ça supprime bien les affichages des autres
      output$valeur_affiche <- renderText({
        paste(NULL)
      })
      return(tickets)
    } else {
      return(NULL)  # Masquer le tableau pour d'autres options choisies
    }
  })

  # Fonction pour créer le graphique
  output$histogram <- renderPlotly({
    # Filtrer les valeurs non finies
    filtered_tickets <- tickets

    # Ajouter l'option pour afficher les valeurs
    if (input$variable == "afficher_valeurs") {
      return(NULL)  # Ne pas générer de graphique si l'option est "afficher_valeurs"
    }

    filtered_tickets <- filtered_tickets %>% filter(!is.na(!!sym(input$variable)))

    if (input$variable == "date_creation" && input$temps == "mois") {
      #Calcule de la moyenne
      liste_mois = filtered_tickets$mois
      nombre_mois = length(unique(liste_mois))
      moyenne = length(liste_mois)/nombre_mois
      #affichage de la moyenne
      output$valeur_affiche <- renderText({
        paste("Nombre moyen de ticket créé par mois  : ", round(moyenne, 2))
      })

      gg <- ggplot(filtered_tickets, aes_string(x = "mois")) +
        geom_bar(stat = "count", fill = "skyblue", color = "black") +
        labs(title = "Distribution du nombre de tickets par mois",
             x = "Mois",
             y = "Nombre de Tickets") +
        theme_minimal()
      ggplotly(gg)%>%
        layout(
          paper_bgcolor = "#BDCDD6",  # Couleur de fond du graphique
          plot_bgcolor = "#BDCDD6"   # Couleur de fond du cadre du graphique
        )

    }

    else if (input$variable == "date_creation" && input$temps == "annee") {
      #Calcule de la moyenne
      liste_annee = filtered_tickets$annee
      nombre_annee = length(unique(liste_annee))
      moyenne = length(liste_annee)/nombre_annee

      #affichage de la moyenne
      output$valeur_affiche <- renderText({
        paste("Nombre moyen de ticket créé par année : ", round(moyenne, 2))
      })

      gg <- ggplot(filtered_tickets, aes_string(x = "year(as.Date(date_creation))")) +
        geom_bar(stat = "count", fill = "skyblue", color = "black") +
        labs(title = "Distribution du nombre de tickets par année",
             x = "Année",
             y = "Nombre de Tickets") +
        theme_minimal()
      ggplotly(gg)%>%
        layout(
          paper_bgcolor = "#BDCDD6",  # Couleur de fond du graphique
          plot_bgcolor = "#BDCDD6"   # Couleur de fond du cadre du graphique
        )
    }

    else if (input$variable == "duree_resolution") {
      # Créer une colonne pour la durée de résolution
      tickets$duree_resolution <- as.numeric(difftime(tickets$date_fermeture, tickets$date_creation, units = "days"))

      # Définir des catégories pour la durée de résolution
      categories <- cut(tickets$duree_resolution, breaks = c(-Inf, 7, 30, Inf),labels = c("Moins d'une semaine", "Entre une semaine et un mois", "Plus d'un mois"), include.lowest = TRUE)

      # Compter le nombre de tickets dans chaque catégorie
      count_by_category <- table(categories)
      moins_une_semaine <- count_by_category["Moins d'une semaine"]
      une_semaine_un_mois <- count_by_category["Entre une semaine et un mois"]
      plus_un_mois <- count_by_category["Plus d'un mois"]

      #Affichage des valeurs
      output$valeur_affiche <- renderText({
        paste("Nombre de tickets résolus en moins d'une semaine : ", moins_une_semaine,
              "<br>Nombre de tickets résolus entre une semaine et un mois: ", une_semaine_un_mois,
              "<br>Nombre de tickets résolus en plus d'un mois: ", plus_un_mois)
      })

      # Créer le camembert avec plot_ly
      plot_ly(labels = names(count_by_category), values = count_by_category, type = "pie") %>%
        layout(
          title = "Répartition de la durée de résolution des tickets",
          paper_bgcolor = "#BDCDD6",  # Couleur de fond du graphique
          plot_bgcolor = "#BDCDD6"    # Couleur de fond du cadre du graphique
        )
    }

    else if (input$variable == "status") {
      # Compter les occurrences de chaque statut
      status_counts <- count(filtered_tickets, status)
      nbr_tickets_ferme <- sum(filtered_tickets$status == "fermé")
      nbr_tickets_encours <- sum(filtered_tickets$status == "en cours de traitement")
      #affichage de les valeurs
      output$valeur_affiche <- renderText({
        paste("Nombre de tickets en cours de traitement : ", nbr_tickets_encours,
              "<br>Nombre de tickets fermés : ", nbr_tickets_ferme)
      })


      # Créer le camembert avec plot_ly en utilisant les données de comptage
      plot_ly(data = status_counts, labels = ~status, values = ~n) %>%
        add_pie() %>%
      layout(
        title = "Répartition des statuts des tickets",
        paper_bgcolor = "#BDCDD6",  # Couleur de fond du graphique
        plot_bgcolor = "#BDCDD6"    # Couleur de fond du cadre du graphique
      )
    }
    else if(input$variable == "heure"){
      #Calcule de la moyenne
      liste_heure = filtered_tickets$heure
      nombre_heure = length(unique(liste_heure))
      moyenne = length(liste_heure)/nombre_heure
      #affichage de la moyenne
      output$valeur_affiche <- renderText({
        paste("Moyenne  : ", round(moyenne, 2))
      })

      gg <- ggplot(filtered_tickets, aes_string(x = input$variable)) +
        geom_bar(stat = "count", fill = "skyblue", color = "black") +
        labs(title = paste("Distribution du nombre de tickets par", input$variable),
             x = input$variable,
             y = "Nombre de Tickets") +
        theme_minimal()
      ggplotly(gg)%>%
        layout(
          paper_bgcolor = "#BDCDD6",  # Couleur de fond du graphique
          plot_bgcolor = "#BDCDD6"   # Couleur de fond du cadre du graphique
        )
    }
    else {
      #Calcule des %
      nbr_ticket_faible <- sum(filtered_tickets$priorité == "faible")
      nbr_ticket_moyenne <- sum(filtered_tickets$priorité == "moyenne")
      nbr_ticket_haute <- sum(filtered_tickets$priorité == "haute")

      # Calcul du nombre total de tickets
      total_tickets <- nrow(filtered_tickets)

      # Calcul des pourcentages
      pourcentage_faible <- (nbr_ticket_faible / total_tickets) * 100
      pourcentage_moyenne <- (nbr_ticket_moyenne / total_tickets) * 100
      pourcentage_haute <- (nbr_ticket_haute / total_tickets) * 100

      # Affichage des %
      output$valeur_affiche <- renderText({
        paste("% de priorité faible : ", round(pourcentage_faible, 2),
              "<br>% de prioritémoyenne : ", round(pourcentage_moyenne, 2),
              "<br>% de priorité haute : ", round(pourcentage_haute, 2))
      })

      gg <- ggplot(filtered_tickets, aes_string(x = input$variable)) +
        geom_bar(stat = "count", fill = "skyblue", color = "black") +
        labs(title = paste("Distribution du nombre de tickets par", input$variable),
             x = input$variable,
             y = "Nombre de Tickets") +
        theme_minimal()
      ggplotly(gg)%>%
        layout(
          paper_bgcolor = "#BDCDD6",  # Couleur de fond du graphique
          plot_bgcolor = "#BDCDD6"   # Couleur de fond du cadre du graphique
        )
    }
  })
}



# Lancer l'application Shiny
shinyApp(ui = ui, server = server)