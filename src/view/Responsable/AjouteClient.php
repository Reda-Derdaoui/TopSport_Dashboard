<?php
session_start();

include __DIR__ . '/dashData.php';
include __DIR__ . "../../../Modules/Connecter.php";
include __DIR__ . "../../../controller/activiteTable.php";
include __DIR__ . "../../../controller/typeAbonnementConfig.php";
include __DIR__ . "../../../controller/AdherantController.php";

if (!isset($_SESSION['userName']) || !isset($_SESSION['logged_in'])) {

    header("Location: ../../view/login.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/style.css">
    <link
        rel="icon"
        type="image/x-icon"
        href="../assets/images/top-sport-noBack.png" />
    <title>Ajouter | Adherent</title>
</head>

<body>
    <div class="flex gap-10 h-screen bg-blue-100">
        <!-- side bar -->
        <div class="flex flex-col h-screen gap-3 w-40 p-2 bg-gray-100">
            <div class="flex items-center justify-center gap-5 border-b-2 border-blue-200 ">
                <img class="h-15 mb-10 "
                    src="../assets/images/top-sport-noBack.png"
                    alt="TopSport">
            </div>

            <?php foreach ($sideBarView as $sideBar): ?>
                <div class="flex items-center gap-3 hover:bg-blue-100 hover:rounded-2xl px-4 py-2.5
            <?= (basename($sideBar["link"]) == $currentPage)
                    ? 'bg-blue-200 rounded-2xl'
                    : 'hover:bg-blue-100 hover:rounded-2xl' ?>
                ">
                    <a href=<?= $sideBar["link"] ?>>
                        <img class="h-6 w-6"
                            src=<?= $sideBar["img"] ?>
                            alt="home">
                    </a>
                    <a class="font-medium hover:text-blue-700 text-center text-sm "
                        href=<?= $sideBar["link"] ?>> <?= $sideBar["name"] ?>
                    </a>
                </div>
            <?php endforeach;  ?>
        </div>

        <div class="flex flex-col mt-5">
            <!-- Up bar -->

            <div class="flex justify-between items-center bg-gray-100 gap-5 w-300 p-5 rounded-xl">
                <div class="flex justify-center items-center gap-3">
                    <h1 class="text-xl text-blue-700 font-bold">TOP SPORT</h1>
                </div>

                <div class="flex justify-center items-center gap-5">
                    <form action="../../controller/logoutController.php" method="POST">
                        <button
                            type="submit"
                            name="logout"
                            class=" cursor-pointer group flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition-all duration-300 ease-in-out bg-linear-to-r from-red-500 to-red-600 rounded-lg shadow-md shadow-red-500/30 hover:from-red-600 hover:to-red-700 hover:shadow-lg hover:-translate-y-0.5 hover:shadow-red-500/40 focus:ring-4 focus:ring-red-500/50 focus:outline-none dark:shadow-red-900/50">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-7 h-7 transition-transform duration-300 group-hover:-translate-x-1"
                                viewBox="0 0 512 512">
                                <path d="M304 336v40a40 40 0 01-40 40H104a40 40 0 01-40-40V136a40 40 0 0140-40h152c22.09 0 48 17.91 48 40v40M368 336l80-80-80-80M176 256h256"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="32" />
                            </svg>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>

            <!-- Prix check -->
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

            <!-- Assurance check  -->
            <?php if (isset($errorAssurance)): ?>
                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
                    <span class="flex-1 text-sm font-medium"><?php echo $errorAssurance; ?></span>
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

            <!-- Date Naissance check -->
            <?php if (isset($errorDateNaissance)): ?>
                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
                    <span class="flex-1 text-sm font-medium"><?php echo $errorDateNaissance; ?></span>
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

            <!-- numero de telephone check -->
            <?php if (isset($errorTele)): ?>
                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
                    <span class="flex-1 text-sm font-medium"><?php echo $errorTele; ?></span>
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

            <!-- adherent check  -->
            <?php if (isset($error)): ?>
                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
                    <span class="flex-1 text-sm font-medium"><?php echo $error; ?></span>
                    <div class="ml-4 items-center flex">
                        <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                            onclick="return this.parentNode.parentNode.remove()">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            <?php elseif (isset($validation)): ?>
                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-green-800 bg-green-100 border border-green-200 rounded-lg shadow-sm" role="alert">
                    <span class="text-green-600 font-semibold text-md text-center"><?php echo $validation; ?></span>
                    <div class="ml-4 flex items-center ">
                        <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                            onclick="return this.parentNode.parentNode.remove()">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="green">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- nom and prenom check for numbers input -->
            <?php if (isset($numberError)): ?>
                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
                    <span class="flex-1 text-sm font-medium"><?php echo $numberError; ?></span>
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

            <!-- form -->
            <form class="flex justify-around gap-10 mt-5 p-6 rounded-2xl bg-white shadow-md border border-slate-200" action="AjouteClient.php" method="POST">

                <div class="flex flex-col gap-8 w-full">
                    <input type="hidden" id="id" name="id">
                    <div class="flex flex-col gap-2">
                        <label class="text-slate-600 text-lg font-bold" for="prenom">Prénom</label>
                        <input id="prenom" type="text" name="prenom"
                            class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-slate-600 text-lg font-bold" for="nom">Nom</label>
                        <input id="nom" type="text" name="nom"
                            class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-slate-600 text-lg font-bold" for="tele">Téléphone</label>
                        <input id="tele" type="number" name="tele"
                            class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-slate-600 text-lg font-bold" for="date">Date de naissance</label>
                        <input id="dateN" type="date" name="dateNaissance"
                            class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-slate-600 text-lg font-bold" for="activite">Activité</label>
                        <select name="Activite" id="activite"
                            class="cursor-pointer border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-white">
                            <?php foreach ($activites as $row): ?>
                                <option value="<?= $row["Libelle_Activite"] ?>"><?= $row["Libelle_Activite"] ?></option>
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
                                <option value="<?= $row["Libelle_TAbonnement"] ?>"><?= $row["Libelle_TAbonnement"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-slate-600 text-lg font-bold">Date de début</label>
                        <input type="date" id="date" name="date"
                            class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-slate-600 text-lg font-bold">Prix adhérence </label>
                        <input type="number" id="prix" name="prix"
                            class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-slate-600 text-lg font-bold">Assurance</label>
                        <input type="number" id="assurance" name="assurance"
                            class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    </div>

                    <input class="w-33 px-4.5 py-1 text-white text-lg bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition-colors duration-200 cursor-pointer"
                        type="submit"
                        value="Ajouter"
                        name="addAdherent">

                </div>

            </form>
        </div>
    </div>



</body>

</html>