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
  titlePanel("Analyse des Tickets"),
  sidebarLayout(
    sidebarPanel(
      selectInput("variable", "Choisir une variable :", choices = c("priorité", "date_creation", "heure", "status", "duree_resolution", "afficher_valeurs")),
      conditionalPanel(
        condition = "input.variable == 'date_creation'",
        radioButtons("temps", "Choisir une période :", choices = c("Mois" = "mois", "Année" = "annee"))
      ),
      hr(),
      helpText("Sélectionnez une variable pour l'analyse.")
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
      gg <- ggplot(filtered_tickets, aes_string(x = "mois")) +
        geom_bar(stat = "count", fill = "skyblue", color = "black") +
        labs(title = "Distribution du nombre de tickets par mois",
             x = "Mois",
             y = "Nombre de Tickets") +
        theme_minimal()
      ggplotly(gg)
    } 
    
    else if (input$variable == "date_creation" && input$temps == "annee") {
      gg <- ggplot(filtered_tickets, aes_string(x = "year(as.Date(date_creation))")) +
        geom_bar(stat = "count", fill = "skyblue", color = "black") +
        labs(title = "Distribution du nombre de tickets par année",
             x = "Année",
             y = "Nombre de Tickets") +
        theme_minimal()
      ggplotly(gg)
    } 
    
    else if (input$variable == "duree_resolution") {
      # Créer une colonne pour la durée de résolution
      tickets$duree_resolution <- as.numeric(difftime(tickets$date_fermeture, tickets$date_creation, units = "days"))
      
      # Définir des catégories pour la durée de résolution
      categories <- cut(tickets$duree_resolution, breaks = c(-Inf, 7, 30, Inf),labels = c("Moins d'une semaine", "Entre une semaine et un mois", "Plus d'un mois"), include.lowest = TRUE)
      
      # Compter le nombre de tickets dans chaque catégorie
      count_by_category <- table(categories)
      
      # Créer le camembert avec plot_ly
      plot_ly(labels = names(count_by_category), values = count_by_category, type = "pie") %>%
        layout(title = "Répartition de la durée de résolution des tickets")
    } 
    
    else if (input$variable == "status") {
      # Compter les occurrences de chaque statut
      status_counts <- count(filtered_tickets, status)
      
      # Créer le camembert avec plot_ly en utilisant les données de comptage
      plot_ly(data = status_counts, labels = ~status, values = ~n) %>%
        add_pie() %>%
        layout(title = "Répartition des statuts des tickets")
    } 
    
    else {
      gg <- ggplot(filtered_tickets, aes_string(x = input$variable)) +
        geom_bar(stat = "count", fill = "skyblue", color = "black") +
        labs(title = paste("Distribution du nombre de tickets par", input$variable),
             x = input$variable,
             y = "Nombre de Tickets") +
        theme_minimal()
      ggplotly(gg)
    }
  })
}



# Lancer l'application Shiny
shinyApp(ui = ui, server = server)
