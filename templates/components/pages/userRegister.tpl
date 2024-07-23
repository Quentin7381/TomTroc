<main <?=$attributes?>>
    <form action="/user/request/register" method="post">
        <label for="name">Pseudo</label>
        <input type="text" name="name" id="name" required>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">S'inscrire</button>
    </form>
</main>