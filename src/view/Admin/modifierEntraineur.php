<?php
include __DIR__ . "../../../controller/modifierEntraineurController.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        rel="icon"
        type="image/x-icon"
        href="../assets/images/top-sport-noBack.png" />
    <link rel="stylesheet" href="../../../css/style.css">
    <title>Modifier | Entraîneurs</title>
</head>

<body class="bg-blue-100">
    <div class="flex items-center gap-[20%] mt-5">
        <a href="Responsables.php">
            <img class="h-20 ml-15 mt-0.5" src="../assets/images/top-sport-noBack.png"
                alt="Top Sport">
        </a>
        <h1 class="text-2xl font-serif border-b rounded-2xl shadow-black p-1.5">Modification des informations d'Entraîneurs</h1>
    </div>

    <div class="relative">
        <div class="absolute top-5 left-115">
            <a href="Entraineurs.php" class="inline-block">
                <button class="p-2 text-slate-500 hover:text-slate-800 bg-transparent hover:bg-slate-100 active:bg-slate-200 rounded-full transition-all duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <img class="h-7 w-7" src="../assets/icons/arrow-left.svg" alt="Retour">
                </button>
            </a>
        </div>
        <form class="flex flex-col items-center rounded-xl bg-white m-auto border h-auto w-150 gap-5 p-1.5 border-blue-100 shadow-2xl shadow-blue-400"
            action="../../controller/modifierEntraineurController.php"
            method="POST">

            <input type="hidden" name="id" value=<?= $id ?>>
            <div class="flex flex-col gap-2">
                <label for="prenom">Prénom: </label>
                <input class="border rounded-lg w-55 p-1" id="prenom" type="text" name="prenom" value="<?= $prenom ?>">
            </div>

            <div class="flex flex-col gap-2">
                <label for="nom">Nom: </label>
                <input class="border rounded-lg w-55 p-1" id="nom" type="text" name="nom" value="<?= $nom ?>">
            </div>

            <div class="flex flex-col gap-2">
                <label for="tele">Téléphone:</label>
                <input class="border rounded-lg w-55 p-1" id="tele" type="number" name="tele" value="<?= $tel ?>">
            </div>

            <div class="flex flex-col gap-2">
                <label for="DateN">Date de naissance: </label>
                <input class="border rounded-lg w-55 p-1" id="DateN" type="date" name="dateNaissance" value="<?= $date ?>">
            </div>

            <div class="flex flex-col gap-2">
                <label for="specialite">Spécialité: </label>
                <input class="border rounded-lg w-55 p-1" id="specialite" type="text" name="specialite" value="<?= $specialite ?>">
            </div>
            <input class="save px-4.5 py-1 text-white text-lg bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200 cursor-pointer"
                type="submit"
                value="Enregistrer">
        </form>
    </div>

    <script>
        function deleteConfirm(event) {
            if (event.target.closest(".save")) {
                const ok = confirm("Êtes-vous sûr de vouloir enregistrer les modifications pour cet entraineur ?");

                if (!ok) {
                    event.preventDefault();
                }
            }
        }

        document.addEventListener("click", deleteConfirm);
    </script>
</body>

</html>