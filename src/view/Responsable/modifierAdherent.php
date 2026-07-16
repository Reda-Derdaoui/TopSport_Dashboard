<?php
include_once __DIR__ . "../../../controller/activiteTable.php";
include_once __DIR__ . "../../../controller/typeAbonnementConfig.php";
include __DIR__ . "../../../controller/modifierAdherentController.php";
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
    <title>Adhérent | Modifier</title>
</head>

<body>

    <div class="flex items-center gap-[20%] mt-5">
        <a href="Client.php">
            <img class="h-20 ml-15 mt-0.5" src="../assets/images/top-sport-noBack.png"
                alt="Top Sport">
        </a>
        <h1 class="text-2xl font-serif border-b rounded-2xl shadow-black p-1.5">Modification des informations d'adhérent</h1>
    </div>
    <?php if (isset($errorPrice)): ?>
        <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
            <span class="flex-1 text-sm font-medium"><?php echo $errorPrice; ?></span>
            <div class="ml-4 items-center flex">
                <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                    onclick="return this.parentNode.parentNode.remove()">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endif;  ?>

    <div class="relative">
        <div class="absolute -top-20 right-5">
            <a href="Client.php" class="inline-block">
                <button class="p-2 text-slate-500 hover:text-slate-800 bg-transparent hover:bg-slate-100 active:bg-slate-200 rounded-full transition-all duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <img class="h-9 w-9" src="../assets/icons/arrow-left.svg" alt="Retour">
                </button>
            </a>
        </div>

        <form class="flex justify-around gap-10 mt-5 p-6 rounded-2xl bg-white shadow-xl border border-slate-300"
            action="../../controller/modifierAdherentController.php" method="POST">

            <div class="flex flex-col gap-8 w-full">
                <input type="hidden" id="id" name="idAdherent" value="<?= $idAdherent ?>">
                <div class="flex flex-col gap-2">
                    <label class="text-slate-600 text-lg font-bold" for="prenom">Prénom</label>
                    <input id="prenom" type="text" name="prenom" value="<?= $prenom ?>"
                        class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-slate-600 text-lg font-bold" for="nom">Nom</label>
                    <input id="nom" type="text" name="nom" value="<?= $nom  ?>"
                        class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-slate-600 text-lg font-bold" for="tele">Téléphone</label>
                    <input id="tele" type="number" name="tele" value="<?= $tele ?>"
                        class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-slate-600 text-lg font-bold" for="date">Date de naissance</label>
                    <input id="dateN" type="date" name="dateNaissance" value="<?= $datNaissance ?>"
                        class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-slate-600 text-lg font-bold" for="activite">Activité</label>
                    <select name="Activite" id="activite"
                        class="cursor-pointer border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-white">
                        <?php foreach ($activites as $row): ?>
                            <option value="<?= $row["Libelle_Activite"] ?>"> <?= $row["Libelle_Activite"] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-8 w-full">
                <div class="flex flex-col gap-2">
                    <label class="text-slate-600 text-lg font-bold" for="type">Type abonnement</label>
                    <select name="Type_Abon" id="type"
                        class="cursor-pointer border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-white">
                        <?php foreach ($typeAbonnement as $row): ?>
                            <option value="<?= $row["Libelle_TAbonnement"] ?>">
                                <?= $row["Libelle_TAbonnement"] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <input type="hidden" name="abonn" value="<?= $idAbonnement ?>">
                    <label class="text-slate-600 text-lg font-semibold">Date de début</label>
                    <input type="date" id="date" name="date" value="<?= $dateDebut ?>"
                        class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-slate-600 text-lg font-bold">Prix adhérence</label>
                    <input type="number" id="prix" name="prix" value="<?= $prix ?>"
                        class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-slate-600 text-lg font-bold">Assurance</label>
                    <input type="number" id="assurance" name="assurance" value="<?= $assurance ?>"
                        class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>

                <input class="save px-4.5 py-1 w-33 text-white text-lg bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200 cursor-pointer"
                    type="submit"
                    value="Enregistrer">
            </div>

        </form>
    </div>
    <script>
        function deleteConfirm(event) {
            if (event.target.closest(".save")) {
                const ok = confirm("Êtes-vous sûr de vouloir enregistrer les modifications pour cet adhérent?");

                if (!ok) {
                    event.preventDefault();
                }
            }
        }

        document.addEventListener("click", deleteConfirm);
    </script>
</body>

</html>