<main <?=$attributes?>>
    <form action="/user/request/login" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Se connecter</button>
    </form>
</main>