# Les problèmes rencontrés


## Les formulaires / checkboxes

1. Le template acheté créé des bugs, dus surement au remplacement des checkboxes par des checkboxes custom dans le CSS.

- le label doit etre apres l'input
- ils doivent être enrobé d'une div

```html
    <div>
        <input type="checkbox">
        <label></label>
    </div>
```

2. la solution pour pouvoir faire ça 

j'ai utlisé le "helper" du template twig de symfony. il faut donné le nom du widget pour pouvoir détailler derriere ce que l'on attend. Dans le cas contraire:
- soit on se retrouve avec deux fois les même inputs
- soit on le désactive du formType: Ne fonctionne pas car erreur de champs supplémentaire + erreur token.

Voici comment détailler grâce au Helper
```html
    {{ form_row(form.username) }}
    {# Ici le helper et le detail #}
    <div name="{{ field_name(form.roles) }}">
        {% set i = 0 %}
        {% for label, value in field_choices(form.roles) %}
        <div>
            <input type="checkbox" id="user_roles_{{i }}" name="user[roles][]" value="{{ value }}" {{ value in user.roles ? 'checked' : ''}}>
            <label for="user_roles_{{i }}">{{ label }}</label>
        </div>
        {% set i = i + 1 %}
        {% endfor %}
    </div>
    {{ form_row(form.password) }}
```