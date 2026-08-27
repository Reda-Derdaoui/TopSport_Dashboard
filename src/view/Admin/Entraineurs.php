<?php

include __DIR__ . '/dashData.php';
include __DIR__ . ".../../../controller/EntraineurController.php";
include __DIR__ . "../../../controller/EntraineurTable.php";
include __DIR__ . "../../../Modules/Connecter.php";

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
    <link rel="icon" type="image/x-icon" href="../assets/images/top-sport-noBack.png" />
    <title>Entraineurs</title>
</head>

<body>
    <div class="flex  gap-10 bg-blue-100 h-screen">

        <!-- side bar -->
        <!-- Mobile menu button -->
        <button id="sidebarToggle" type="button" class="hidden max-md:flex fixed top-4 left-4 z-50
           items-center justify-center w-10 h-10
           bg-blue-500 text-white rounded-xl shadow-md
           hover:bg-blue-600 transition-colors duration-200" aria-label="Ouvrir le menu" aria-expanded="false">

            <span class="text-2xl">☰</span>
        </button>

        <!-- Overlay -->
        <div id="sidebarOverlay" class="hidden max-md:fixed max-md:inset-0 max-md:bg-black/30 max-md:z-40">
        </div>

        <!-- Sidebar -->
        <div id="sidebar" class="flex flex-col h-screen gap-3 w-45 p-2 bg-gray-100
           max-md:fixed max-md:top-0 max-md:left-0 max-md:z-50
           max-md:w-64 max-md:h-screen
           max-md:-translate-x-full
           max-md:transition-transform max-md:duration-300">

            <div class="flex items-center justify-center gap-5 border-b-2 border-blue-200">
                <img class="h-15 mb-10 max-md:hidden" src="../assets/images/top-sport-noBack.png" alt="TopSport">
            </div>

            <?php foreach ($sideBarView as $sideBar): ?>

                <div class="flex items-center gap-3 hover:bg-blue-100 hover:rounded-2xl px-4 py-2.5
            <?= (basename($sideBar["link"]) == $currentPage)
                ? 'bg-blue-200 rounded-2xl'
                : 'hover:bg-blue-100 hover:rounded-2xl' ?>">

                    <a href="<?= $sideBar["link"] ?>">
                        <img class="h-6 w-6" src="<?= $sideBar["img"] ?>" alt="home">
                    </a>

                    <a class="font-medium hover:text-blue-700 text-center text-sm" href="<?= $sideBar["link"] ?>">
                        <?= $sideBar["name"] ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex flex-col mt-5
            max-xl:mr-3
            max-lg:mr-2
            max-md:mt-3 max-md:w-full
            max-sm:mt-2">

            <!-- Up bar -->

            <div class="flex justify-between items-center bg-gray-100 gap-5 w-300 p-5 rounded-xl
                max-xl:w-full max-xl:p-4
                max-lg:gap-3 max-lg:p-3
                max-md:pl-16
                max-sm:p-2 max-sm:pl-14 max-sm:gap-2">

                <div class="flex justify-center items-center gap-3
                    max-sm:gap-1">

                    <img class="h-10
                        max-md:h-8
                        max-sm:h-7" src="../assets/images/loupe.png" alt="serch">

                    <input class="border border-slate-300 rounded-lg px-3 py-2
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                       outline-none transition
                       max-md:w-48
                       max-sm:w-32 max-sm:px-2 max-sm:py-1.5" id="filter" type="text" placeholder="Searche">
                </div>

                <div class="flex justify-center items-center gap-5
                    max-md:gap-2">

                    <form action="../../controller/logoutController.php" method="POST">

                        <button type="submit" name="logout" class="cursor-pointer group flex items-center justify-center gap-2
                           px-5 py-2.5 text-sm font-medium text-white
                           transition-all duration-300 ease-in-out
                           bg-linear-to-r from-red-500 to-red-600
                           rounded-lg shadow-md shadow-red-500/30
                           hover:from-red-600 hover:to-red-700
                           hover:shadow-lg hover:-translate-y-0.5
                           hover:shadow-red-500/40
                           focus:ring-4 focus:ring-red-500/50 focus:outline-none
                           dark:shadow-red-900/50
                           max-md:px-3 max-md:py-2
                           max-sm:px-2 max-sm:py-1.5 max-sm:text-xs max-sm:gap-1">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 transition-transform duration-300 group-hover:-translate-x-1
                               max-md:w-6 max-md:h-6
                               max-sm:w-5 max-sm:h-5" viewBox="0 0 512 512">

                                <path
                                    d="M304 336v40a40 40 0 01-40 40H104a40 40 0 01-40-40V136a40 40 0 0140-40h152c22.09 0 48 17.91 48 40v40M368 336l80-80-80-80M176 256h256"
                                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="32" />
                            </svg>

                            Se déconnecter
                        </button>

                    </form>
                </div>
            </div>


            <!-- check date naissance -->

            <?php if (isset($errorDateNaissance)): ?>

                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2
                    text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm
                    max-sm:p-2" role="alert">

                    <span class="flex-1 text-sm font-medium">
                        <?php echo $errorDateNaissance; ?>
                    </span>

                    <div class="ml-4 items-center flex">
                        <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                            onclick="return this.parentNode.parentNode.remove()">

                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>
                    </div>
                </div>

            <?php endif; ?>


            <!-- Check telephone -->

            <?php if (isset($errorTele)): ?>

                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2
                    text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm
                    max-sm:p-2" role="alert">

                    <span class="flex-1 text-sm font-medium">
                        <?php echo $errorTele; ?>
                    </span>

                    <div class="ml-4 items-center flex">
                        <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                            onclick="return this.parentNode.parentNode.remove()">

                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 1.414 1.414 1.414L11.414 10l4.293 4.293a1 1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>
                    </div>
                </div>

            <?php endif; ?>


            <!-- nom and prenom check for numbers input -->

            <?php if (isset($numberError)): ?>

                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2
                    text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm
                    max-sm:p-2" role="alert">

                    <span class="flex-1 text-sm font-medium">
                        <?php echo $numberError; ?>
                    </span>

                    <div class="ml-4 items-center flex">
                        <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                            onclick="return this.parentNode.parentNode.remove()">

                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>
                    </div>
                </div>

            <?php endif; ?>


            <!-- check entraineur -->

            <?php if (isset($error)): ?>

                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2
                    text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm
                    max-sm:p-2" role="alert">

                    <span class="flex-1 text-sm font-medium">
                        <?php echo $error; ?>
                    </span>

                    <div class="ml-4 items-center flex">
                        <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                            onclick="return this.parentNode.parentNode.remove()">

                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 01-1.414-1.414 1.414 1.414 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>
                    </div>
                </div>

            <?php elseif (isset($validation)): ?>

                <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2
                    text-green-800 bg-green-100 border border-green-200 rounded-lg shadow-sm
                    max-sm:p-2" role="alert">

                    <span class="text-green-600 font-semibold text-md text-center">
                        <?php echo $validation; ?>
                    </span>

                    <div class="ml-4 flex items-center">

                        <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                            onclick="return this.parentNode.parentNode.remove()">

                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="green">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>

                    </div>
                </div>

            <?php endif; ?>


            <!-- Form -->

            <form class="flex justify-center gap-20 mt-5 p-5 rounded-xl bg-white
               max-xl:gap-10
               max-lg:gap-6
               max-md:flex-col max-md:gap-5 max-md:w-full
               max-sm:p-3" action="Entraineurs.php" method="POST">

                <div class="flex flex-col gap-5
                    max-md:w-full">

                    <div class="flex flex-col gap-5">
                        <label for="prenom">Prénom: </label>

                        <input class="border border-slate-300 rounded-lg px-3 py-2
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           outline-none transition
                           max-md:w-full" id="prenom" type="text" name="prenom">
                    </div>

                    <div class="flex flex-col gap-5">
                        <label for="nom">Nom: </label>

                        <input class="border border-slate-300 rounded-lg px-3 py-2
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           outline-none transition
                           max-md:w-full" id="nom" type="text" name="nom">
                    </div>

                </div>


                <div class="flex flex-col gap-5
                    max-md:w-full">

                    <div class="flex flex-col gap-5">
                        <label for="tele">Téléphone:</label>

                        <input class="border border-slate-300 rounded-lg px-3 py-2
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           outline-none transition
                           max-md:w-full" id="tele" type="number" name="tele">
                    </div>

                    <div class="flex flex-col gap-5">
                        <label for="DateN">Date de naissance: </label>

                        <input class="border border-slate-300 rounded-lg px-3 py-2
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           outline-none transition
                           max-md:w-full" id="DateN" type="date" name="dateNaissance">
                    </div>

                </div>


                <div class="flex flex-col gap-5
                    max-md:w-full">

                    <div class="flex flex-col gap-5">

                        <div class="flex flex-col gap-5">
                            <label for="specialite">Spécialité: </label>

                            <input class="border border-slate-300 rounded-lg px-3 py-2
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                               outline-none transition
                               max-md:w-full" id="specialite" type="text" name="specialite">
                        </div>

                    </div>

                    <input class="px-4.5 py-1 text-white text-lg bg-blue-600 rounded-lg
                       shadow-sm hover:bg-blue-700 transition-colors duration-200 cursor-pointer
                       max-md:w-full" type="submit" value="Ajouter">

                </div>

            </form>


            <!-- Table -->

            <div class="mt-5 overflow-auto w-full">

                <table class="table-auto w-full text-center border border-slate-200 rounded-xl overflow-hidden
                      min-w-225">

                    <thead class="bg-slate-300 border-b border-slate-200">

                        <tr>
                            <td class="text-lg font-semibold p-3 text-slate-800">Nom</td>
                            <td class="text-lg font-semibold p-3 text-slate-800">Prénom</td>
                            <td class="text-lg font-semibold p-3 text-slate-800">Téléphone</td>
                            <td class="text-lg font-semibold p-3 text-slate-800">Date de naissance</td>
                            <td class="text-lg font-semibold p-3 text-slate-800">Spécialité</td>
                            <td class="text-lg font-semibold p-3 text-slate-800">Admin</td>
                            <td class="text-lg font-semibold p-3 text-slate-800">Action</td>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 text-sm text-slate-600">

                        <?php foreach ($entraineurs as $entraineur): ?>

                            <tr class="bg-slate-50 hover:bg-slate-200 transition-colors duration-200">

                                <td class="p-3 font-semibold text-slate-700">
                                    <?= $entraineur["Nom"] ?>
                                </td>

                                <td class="p-3 font-semibold text-slate-700">
                                    <?= $entraineur["Prenom"] ?>
                                </td>

                                <td class="p-3 font-semibold text-slate-700">
                                    <?= $entraineur["Tele"] ?>
                                </td>

                                <td class="p-3 font-semibold text-slate-700">
                                    <?= (new DateTime($entraineur["DateNaissance"]))->format('d-m-Y') ?>
                                </td>

                                <td class="p-3 font-semibold text-slate-700">
                                    <?= $entraineur["Specialite"] ?>
                                </td>

                                <td class="p-3 font-semibold text-slate-700">
                                    <?= $entraineur["admin_prenom"] ?>
                                </td>

                                <td class="flex items-center justify-center gap-2 p-2">

                                    <a href="./modifierEntraineur.php?id=<?= $entraineur["id"] ?>">
                                        <button class="cursor-pointer hover:scale-110 transition-transform duration-200">
                                            <img class="h-8 w-8" src="../assets/icons/editeUser.svg" alt="modifier">
                                        </button>
                                    </a>

                                    <a href="../../controller/suprimerEntraineursContoller.php?id=<?= $entraineur["id"] ?>">
                                        <button
                                            class="suprimer cursor-pointer hover:scale-110 transition-transform duration-200">
                                            <img class="h-8 w-8" src="../assets/icons/delete.svg" alt="suprimer">
                                        </button>
                                    </a>

                                    <a href="../../../lib/FPDF/controller/imprimerEntraineursController.php?id=<?= $entraineur["id"] ?>"
                                        target="_blank">

                                        <button class="cursor-pointer hover:scale-110 transition-transform duration-200">
                                            <img class="h-8 w-8" src="../assets/icons/printer.svg" alt="imprimer">
                                        </button>

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('max-md:-translate-x-full');

            sidebarOverlay.classList.toggle('hidden');

            const isOpen = !sidebar.classList.contains('max-md:-translate-x-full');
            sidebarToggle.setAttribute('aria-expanded', isOpen);
        }

        sidebarToggle.addEventListener('click', toggleSidebar);

        sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
    <script src="../assets/script/filter.js"></script>
    <script src="../assets/script/deleteConfirmartion.js"></script>

</body>

</html>