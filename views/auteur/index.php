{{ include('layouts/header.php', {title: 'Auteurs'}) }}
    <h1>Auteurs</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
            </tr>
        </thead>
        <tbody>
            {% for auteur in auteurs %}
            <tr>
                <td><a href="{{base}}/auteur/show?id={{auteur.id}}">{{ auteur.nom }}</a></td>
                <td>{{ auteur.prenom }}</td>
            </tr>
            {% else %}
            <tr>
                <td colspan="2">Aucun auteur.</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    {% if not guest and (session.user_privilege == 1 or session.user_privilege == 2) %}
        <a href="{{base}}/auteur/create" class="btn">Nouvel auteur</a>
    {% endif %}
    {{ include('layouts/footer.php') }}