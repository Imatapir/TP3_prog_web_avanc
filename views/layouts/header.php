<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>{{ title }}</title>
    <link rel="stylesheet" href="{{asset}}/css/style.css">
</head>
<body{% if not guest and session.user_privilege == 1 %} class="admin"{% endif %}>
    <nav>
        <ul>
            <li><a href="{{base}}/">Acceuil</a></li>
            <li><a href="{{base}}/auteurs">Auteurs</a></li>
            <li><a href="{{base}}/livres">Livres</a></li>
            {% if not guest and session.user_privilege == 1 %}
                <li><a href="{{base}}/clients">Clients</a></li>
            {% endif %}
            <!-- Les vues dependent du privilege de l'utilisateur -->
            {% if not guest and (session.user_privilege == 1 or session.user_privilege == 3) %} 
                <li><a href="{{base}}/commandes">Commandes</a></li>
            {% endif %}

            {% if not guest and session.user_privilege == 1 %}
                <li><a href="{{base}}/journal">Journal</a></li>
            {% endif %}

            {% if guest %}
                <li class="slot"><a href="{{base}}/login">Connexion</a></li>
                <li><a href="{{base}}/user/create">Register</a></li>
            {% else %}
                <li class="slot"><a href="{{base}}/logout">Déconnexion</a></li>
                <li class="user-info">{{ session.user_name }} · {{ session.privilege_name|capitalize }}</li>
            {% endif %}
        </ul>
    </nav>
    <main>