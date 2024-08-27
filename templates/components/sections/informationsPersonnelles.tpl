<section <?=$attributes?>>
    <div class="<?=$bem->e('container')?>">
        <h2>Vos informations personnelles</h2>
        <form action="/user/edit/<?=$v->user_current->id?>" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?=$v->user_current->email?>">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password">
            <label for="name">Pseudo</label>
            <input type="text" name="name" id="name" value="<?=$v->user_current->name?>">
            <button type="submit" class="inverted">Enregistrer</button>
        </form>
    </div>
</section>
