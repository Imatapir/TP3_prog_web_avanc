{{ include('layouts/header.php', {title: 'Inscription'}) }}
    <form method="post">
        <h2>Inscription</h2>
        
        <label>Nom 
            <input type="text" name="name" value="{{ user.name }}">
        </label>
        {% if errors.name is defined %} <span class="error">{{ errors.name }}</span>{% endif %}

        <label>Courriel (Nom d'utilisateur)
            <input type="email" name="username" value="{{ user.username }}">
        </label>
        {% if errors.username is defined %} <span class="error">{{ errors.username }}</span>{% endif %}

        <label>Mot de passe
            <input type="password" name="password">
        </label>
        {% if errors.password is defined %} <span class="error">{{ errors.password }}</span>{% endif %}

        <label>Privilege
            <select name="privilege_id">
                <option value="">Choisir un role</option>
                {% for privilege in privileges %}
                    {% if privilege.id != 1 %}
                    <option value="{{ privilege.id }}" {% if privilege.id == user.privilege_id %}selected{% endif %}>{{ privilege.privilege }}</option>
                    {% endif %}
                {% endfor %}
            </select>
        </label>
        {% if errors.privilege_id is defined %} <span class="error">{{ errors.privilege_id }}</span>{% endif %}
        <input type="submit" class="btn" value="S'inscrire">
    </form>
{{ include('layouts/footer.php') }}