<?php
session_start();

include_once __DIR__ . '/dashData.php';
include __DIR__ . "../../../Modules/Connecter.php";
include __DIR__ . "../../../controller/adminController.php";

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

    <link rel="icon" type="image/x-icon" href="../assets/images/top-sport-noBack.png" />
    <link rel="stylesheet" href="../../../css/style.css">
    <title>Adherent</title>
</head>

<body>
    <!-- Added max-md:gap-0 so the gap disappears when the sidebar turns into a mobile drawer, and overflow-hidden to prevent body scrolling -->
<div class="flex h-screen w-full bg-blue-100 overflow-hidden gap-10 max-xl:gap-6 max-lg:gap-4 max-md:gap-0">

    <!-- Mobile menu button -->
    <button id="sidebarToggle" type="button" class="hidden max-md:flex fixed top-4 left-4 z-50
        items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-xl shadow-md
        hover:bg-blue-600 transition-colors duration-200" aria-label="Ouvrir le menu" aria-expanded="false">
        <span class="text-2xl">☰</span>
    </button>

    <!-- Overlay -->
    <div id="sidebarOverlay" class="hidden max-md:fixed max-md:inset-0 max-md:bg-black/30 max-md:z-40"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="flex flex-col h-full gap-3 w-45 p-4 shrink-0 bg-gray-100
        max-md:fixed max-md:top-0 max-md:left-0 max-md:z-50 max-md:h-screen
        max-md:-translate-x-full max-md:transition-transform max-md:duration-300">

        <div class="flex items-center justify-center gap-5 border-b-2 border-blue-200 pb-2">
            <img class="h-12 mb-8 object-contain max-md:mb-4" src="../assets/images/top-sport-noBack.png" alt="TopSport">
        </div>

        <?php foreach ($sideBarView as $sideBar): ?>
                <div class="flex items-center gap-3 hover:bg-blue-100 hover:rounded-2xl px-4 py-2.5 transition-colors
            <?= (basename($sideBar["link"]) == $currentPage)
                ? 'bg-blue-200 rounded-2xl'
                : 'hover:bg-blue-100 hover:rounded-2xl' ?>">

                    <a href="<?= $sideBar["link"] ?>" class="shrink-0">
                        <img class="h-6 w-6" src="<?= $sideBar["img"] ?>" alt="home">
                    </a>
                    <a class="font-medium hover:text-blue-700 text-center text-sm" href="<?= $sideBar["link"] ?>">
                        <?= $sideBar["name"] ?>
                    </a>
                </div>
        <?php endforeach; ?>
    </div>

    <!-- Main Content Wrapper -->
    <!-- Added flex-1 and overflow-y-auto so the right side scrolls perfectly independently of the sidebar -->
    <div class="flex-1 flex flex-col h-full overflow-y-auto w-full pt-5 pr-5 pb-10 max-xl:pr-4 max-lg:pr-3 max-md:px-4 max-md:pt-3 max-sm:px-2 max-sm:pt-2">

        <!-- Up Bar -->
        <div class="flex justify-between items-center bg-gray-100 gap-5 w-full p-5 rounded-xl shadow-sm
            max-xl:p-4 max-lg:gap-3 max-lg:p-3 max-md:pl-16 max-sm:p-2 max-sm:pl-14 max-sm:gap-2 shrink-0">

            <div class="flex justify-center items-center gap-3 max-sm:gap-1">
                <img class="h-10 max-md:h-8 max-sm:h-7" src="../assets/images/loupe.png" alt="search">
                <input id="filter"
                    class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition max-md:w-48 max-sm:w-32 max-sm:px-2 max-sm:py-1.5 max-sm:text-sm"
                    type="text" placeholder="Search">
            </div>

            <div class="flex justify-center items-center gap-5 max-md:gap-2">
                <form action="../../controller/logoutController.php" method="POST">
                    <button type="submit" name="logout"
                        class="cursor-pointer group flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition-all duration-300 ease-in-out bg-linear-to-r from-red-500 to-red-600 rounded-lg shadow-md shadow-red-500/30 hover:from-red-600 hover:to-red-700 hover:shadow-lg hover:-translate-y-0.5 hover:shadow-red-500/40 focus:ring-4 focus:ring-red-500/50 focus:outline-none dark:shadow-red-900/50 max-md:px-3 max-md:py-2 max-sm:px-2 max-sm:py-1.5 max-sm:text-xs max-sm:gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-7 h-7 transition-transform duration-300 group-hover:-translate-x-1 max-md:w-6 max-md:h-6 max-sm:w-5 max-sm:h-5"
                            viewBox="0 0 512 512">
                            <path d="M304 336v40a40 40 0 01-40 40H104a40 40 0 01-40-40V136a40 40 0 0140-40h152c22.09 0 48 17.91 48 40v40M368 336l80-80-80-80M176 256h256" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                        </svg>
                        <span class="max-sm:hidden">Se déconnecter</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Container -->
        <div class="mt-10 w-full overflow-x-auto pb-4 max-xl:mt-8 max-lg:mt-6 max-md:mt-5 max-sm:mt-4 max-sm:pb-2 shrink-0">

            <table id="pagination-table"
                class="w-full text-center border-collapse bg-white rounded-xl overflow-hidden shadow-sm max-md:block max-md:bg-transparent max-md:shadow-none max-md:border-0">

                <!-- Header -->
                <thead class="bg-slate-200 border-b-2 border-slate-300 sticky top-0 z-10 max-md:hidden">
                    <tr>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Prénom</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Nom</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Téléphone</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Date de naissance</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Activité</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Prix</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Type abonnement</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Date de début</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Date de fin</th>
                        <th class="text-sm font-semibold p-3 text-slate-900 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Assurance</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Entraîneur</th>
                        <th class="text-sm font-semibold p-3 text-slate-800 whitespace-nowrap max-xl:p-2.5 max-xl:text-xs max-lg:p-2">Responsable</th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600 max-md:block max-md:divide-y-0 max-md:w-full">

                    <?php foreach ($adherents as $adherent): ?>

                            <tr class="hover:bg-slate-50 transition-colors duration-200 
                            max-md:block max-md:w-full max-md:bg-white max-md:mb-5 max-md:border max-md:border-slate-200 max-md:rounded-xl max-md:shadow-sm max-sm:mb-3">

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Prénom :</span>
                                    <span class="text-right"><?= $adherent["Prenom"] ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Nom :</span>
                                    <span class="text-right"><?= $adherent["Nom"] ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Téléphone :</span>
                                    <span class="text-right"><?= $adherent["Tele"] ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Date de naissance :</span>
                                    <span class="text-right"><?= (new DateTime($adherent["DateNaissance"]))->format('d-m-Y') ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Activité :</span>
                                    <span class="text-right"><?= $adherent["activite"] ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Prix :</span>
                                    <span class="text-right"><?= $adherent["Prix"] . ' Dh' ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Type abonnement :</span>
                                    <span class="text-right"><?= $adherent["type_abonnement"] ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Date de début :</span>
                                    <span class="text-right"><?= (new DateTime($adherent["DateDebut"]))->format('d-m-Y') ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Date de fin :</span>
                                    <span class="text-right"><?= (new DateTime($adherent["DateFin"]))->format('d-m-Y') ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Assurance :</span>
                                    <span class="text-right"><?= $adherent["prixAssurance"] . ' Dh' ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:border-b max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Entraîneur :</span>
                                    <span class="text-right"><?= $adherent["entraineur_nom"] ?></span>
                                </td>

                                <td class="font-semibold text-slate-700 whitespace-nowrap p-3 text-sm max-xl:p-2.5 max-xl:text-xs max-lg:p-2 max-md:whitespace-normal max-md:flex max-md:w-full max-md:justify-between max-md:items-center max-md:py-3 max-md:px-4 max-md:text-sm max-sm:py-2 max-sm:px-3 max-sm:text-xs">
                                    <span class="hidden max-md:block font-bold text-slate-800">Responsable :</span>
                                    <span class="text-right"><?= $adherent["responsable_nom"] ?></span>
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
</body>

</html>