{{ include('layouts/header.php', {title: 'Connexion'}) }}
    <form method="post">
        <h2>Connexion</h2>
        {% if errors.login is defined %}
            <span class="error">{{ errors.login }}</span>
        {% endif %}
        <label>Courriel
            <input type="email" name="username" placeholder="courriel@domaine.example" value="{{ user.username }}">
        </label>
        {% if errors.username is defined %}
            <span class="error">{{ errors.username }}</span>
        {% endif %}
        <label>Mot de passe
            <input type="password" name="password">
        </label>
        {% if errors.password is defined %}
            <span class="error">{{ errors.password }}</span>
        {% endif %}
        <input type="submit" class="btn" value="Se connecter">
    </form>
{{ include('layouts/footer.php') }}