<?php
include __DIR__ . "../../controller/loginController.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        rel="icon"
        type="image/x-icon"
        href="./assets/images/top-sport-noBack.png" />

    <link rel="stylesheet" href="../../css/style.css">
    <title>login</title>
</head>

<body>

    <div class="flex items-center justify-around">
        <div class="left-5 flex flex-col justify-center gap-45 items-center w-[50%] h-screen ">
            <img class="w-[50%]" src="./assets/images/top-sport-noBack.png" alt="TopSport">
            <h1 class="text-4xl font-semibold font-serif">Bienvenue !</h1>
        </div>

        <div class="flex flex-col gap-20 items-center justify-center bg-blue-500 h-screen w-[50%]">
            <h1 class="text-white text-4xl font-semibold font-serif">se connecter</h1>

            <div class="flex flex-col">
                <form id="reset"
                    action="../controller/loginController.php"
                    method="POST">
                    <div class="flex flex-col gap-10">
                        <div class="flex gap-2 items-center">
                            <img class="h-12"
                                src="./assets/images/userName.png"
                                alt="userName">
                            <input class="border-b border-b-white outline-none w-60 p-1 rounded-b-lg"
                                type="text"
                                placeholder="Nom d'utilisateur"
                                name="userName"
                                id="user">
                        </div>

                        <div class="flex gap-2 items-center">
                            <img class="h-12" src="./assets/images/locked.png" alt="Password">
                            <input class="border-b outline-none border-b-white w-60 p-1 rounded-b-lg"
                                type="password"
                                placeholder="Mot de passe"
                                name="password"
                                id="password">
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-10 gap-2">
                        <input class="w-4 h-4 border border-default-medium rounded-xs
                                     bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                            type="checkbox"
                            id="checkBox">
                        <h2 class="text-white text-lg font-semibold">Afficher le mot de passe</h2>
                    </div>

                    <div class="flex gap-20 mt-20 reset">
                        <button
                            id="annuler"
                            type="button"
                            class="w-32 py-2.5 text-xl font-semibold text-slate-500 bg-white border
                            border-slate-200 rounded-2xl cursor-pointer transition-all duration-200
                            hover:bg-slate-50 hover:text-slate-700 hover:border-slate-300
                            active:scale-95 outline-none shadow-sm">
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="w-44 py-3 text-xl font-bold text-blue-700
                              bg-white border-none rounded-2xl cursor-pointer 
                                shadow-[0_20px_50px_rgba(96,165,250,0.4)] transition-all 
                                duration-300 hover:-translate-y-1 hover:shadow-[0_20px_60px_rgba(96,165,250,0.6)] 
                                active:scale-95 outline-none">

                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <script src="./assets/script/password.js"></script>
    </div>
</body>

</html>