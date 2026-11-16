{{ include('layouts/header.php', {title: 'Journal de bord'}) }}
    <h1>Journal de bord</h1>
    <table>
        <thead>
            <tr>
                <th>Date et heure</th>
                <th>Utilisateur</th>
                <th>Adresse IP</th>
                <th>Page</th>
            </tr>
        </thead>
        <tbody>
            {% for visite in visites %}
            <tr>
                <td>{{ visite.date_visite }}</td>
                <td>{{ visite.utilisateur }}</td>
                <td>{{ visite.ip }}</td>
                <td>{{ visite.page }}</td>
            </tr>
            {% else %}
            <tr>
                <td colspan="4">Aucune visite enregistrée.</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
{{ include('layouts/footer.php') }}