<?php
session_start();

include_once __DIR__ . '/dashData.php';
include __DIR__ . "../../../Modules/Connecter.php";
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

    <link rel="icon" type="image/x-icon" href="../assets/images/top-sport-noBack.png" />
    <link rel="stylesheet" href="../../../css/style.css">
    <title>Adherent</title>
</head>

<body>
    <div class="flex gap-5 h-screen bg-blue-100">

        <!-- side bar -->

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
        <!-- Note: changed w-45 to w-64 as 45 isn't standard in Tailwind. Added shrink-0 so it doesn't get squished -->
        <div id="sidebar" class="flex flex-col h-full gap-3 w-45 p-4 shrink-0 bg-gray-100
        max-md:fixed max-md:top-0 max-md:left-0 max-md:z-50
        max-md:-translate-x-full max-md:transition-transform max-md:duration-300">

            <div class="flex items-center justify-center gap-5 border-b-2 border-blue-200 pb-2">
                <img class="h-12 mb-8 object-contain max-md:mb-4" src="../assets/images/top-sport-noBack.png"
                    alt="TopSport">
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

        <div class="flex flex-col mt-5 max-w-full overflow-hidden">
            <div
                class="flex justify-between items-center bg-gray-100 gap-5 w-335 max-w-full max-lg:flex-col max-lg:items-stretch p-5 rounded-xl">

                <div class="flex justify-center items-center gap-3 max-lg:w-full">
                    <img class="h-10" src="../assets/images/loupe.png" alt="serch">
                    <input
                        class="border border-slate-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition w-full max-lg:w-full"
                        type="text" placeholder="Searche" id="filter">
                </div>

                <div class="flex justify-center items-center gap-5 max-lg:w-full">
                    <form action="../../controller/logoutController.php" method="POST" class="max-lg:w-full">
                        <button type="submit" name="logout"
                            class="cursor-pointer group flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition-all duration-300 ease-in-out bg-linear-to-r from-red-500 to-red-600 rounded-lg shadow-md shadow-red-500/30 hover:from-red-600 hover:to-red-700 hover:shadow-lg hover:-translate-y-0.5 hover:shadow-red-500/40 focus:ring-4 focus:ring-red-500/50 focus:outline-none dark:shadow-red-900/50 w-full max-lg:w-full">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-7 h-7 transition-transform duration-300 group-hover:-translate-x-1"
                                viewBox="0 0 512 512">
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

            <div class="mt-10 overflow-x-auto w-full max-w-full">
                <table class="table-auto w-335 text-center border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-300 border-b border-slate-200">
                        <tr>
                            <th class="text-sm font-semibold p-3 text-slate-900">Prénom</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Nom</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Téléphone</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Date de naissance</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Activité</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Prix</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Type abonnement</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Date de début</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Date de fin</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Assurance</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Entraîneur</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Responsable</th>
                            <th class="text-sm font-semibold p-3 text-slate-900">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                        <?php foreach ($adherents as $adherent): ?>
                            <tr class="bg-slate-50 hover:bg-slate-200 transition-colors duration-200">

                                <td class="p-2 font-semibold  text-slate-700"><?= $adherent["Prenom"] ?></td>
                                <td class="p-2 font-semibold text-slate-700"><?= $adherent["Nom"] ?></td>
                                <td class="p-2 font-semibold text-slate-700"><?= $adherent["Tele"] ?></td>
                                <td class="p-2 font-semibold text-slate-700">
                                    <?= (new DateTime($adherent["DateNaissance"]))->format('d-m-Y') ?>
                                </td>
                                <td class="p-2 font-semibold text-slate-700"><?= $adherent["activite"] ?></td>
                                <td class="p-2 font-semibold text-slate-700"><?= $adherent["Prix"] . ' Dh' ?></td>
                                <td class="p-2 font-semibold text-slate-700"><?= $adherent["type_abonnement"] ?></td>
                                <td class="p-2 font-semibold text-slate-700">
                                    <?= (new DateTime($adherent["DateDebut"]))->format('d-m-Y') ?>
                                </td>
                                <td class="p-2 font-semibold text-slate-700">
                                    <?= (new DateTime($adherent["DateFin"]))->format('d-m-Y') ?>
                                </td>
                                <td class="p-2 font-semibold text-slate-700"><?= $adherent["prixAssurance"] . ' Dh' ?></td>
                                <td class="p-2 font-semibold text-slate-700"><?= $adherent["entraineur_nom"] ?></td>
                                <td class="p-2 font-semibold text-slate-700"><?= $adherent["responsable_nom"] ?></td>
                                <td class="p-2">
                                    <div class="flex items-center justify-center ">

                                        <a
                                            href="./modifierAdherent.php?Id_adherent=<?= $adherent["Id_adherent"] ?>&Id_Abonnement=<?= $adherent["Id_Abonnement"] ?>">
                                            <button
                                                class="editBtn cursor-pointer hover:scale-110 transition-transform duration-200">
                                                <img class="h-7" src="../assets/icons/editeUser.svg" alt="modifier">
                                            </button>
                                        </a>

                                        <a
                                            href="../../controller/suprimerAdherent.php?Id_adherent=<?= $adherent["Id_adherent"] ?>">
                                            <button
                                                class="suprimer cursor-pointer hover:scale-110 transition-transform duration-200">
                                                <img class="h-6.5 w-6.5  suprimer" src="../assets/icons/delete.svg"
                                                    alt="suprimer">
                                            </button>
                                        </a>

                                        <a href="../../../lib/FPDF/controller/imprimerAdherentController.php?Id_adherent=<?= $adherent["Id_adherent"] ?>"
                                            target="_blank">
                                            <button
                                                class="cursor-pointer hover:scale-110 transition-transform duration-200">
                                                <img class="w-6.5 h-6.5 " src="../assets/icons/printer.svg" alt="print">
                                            </button>
                                        </a>
                                    </div>
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