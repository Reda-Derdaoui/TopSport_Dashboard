<?php
session_start();

include __DIR__ . '/dashData.php';
include __DIR__ . "../../../controller/planningConfig.php";
include __DIR__ . "../../../controller/activiteTable.php";
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

    <link rel="icon" type="image/x-icon" href="../assets/images/top-sport-noBack.png" />
    <link rel="stylesheet" href="../../../css/style.css">
    <title>Planing</title>
</head>

<body>
    <div class="flex gap-10 h-screen bg-blue-100">

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

        <div class="flex flex-col mt-5 overflow-auto">
            <!-- Up bar -->
            <div class="flex justify-between items-center bg-gray-100 gap-5 w-300 p-5 rounded-xl">
                <div class="flex justify-center items-center gap-3">
                    <h1 class="text-xl text-blue-700 font-bold">TOP SPORT</h1>
                </div>

                <div class="flex justify-center items-center gap-5 ">
                    <form action="../../controller/logoutController.php" method="POST">
                        <button type="submit" name="logout"
                            class=" cursor-pointer group flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition-all duration-300 ease-in-out bg-linear-to-r from-red-500 to-red-600 rounded-lg shadow-md shadow-red-500/30 hover:from-red-600 hover:to-red-700 hover:shadow-lg hover:-translate-y-0.5 hover:shadow-red-500/40 focus:ring-4 focus:ring-red-500/50 focus:outline-none dark:shadow-red-900/50">
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

            <div class="flex flex-col justify-center mx-auto gap-5">
                <?php if (isset($error)): ?>
                    <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm"
                        role="alert">
                        <span class="flex-1 text-sm font-medium"><?php echo $error; ?></span>
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
                <?php elseif (isset($validation)): ?>
                    <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-green-800 bg-green-100 border border-green-200 rounded-lg shadow-sm"
                        role="alert">
                        <span class="text-green-600 font-semibold text-md text-center"><?php echo $validation; ?></span>
                        <div class="ml-4 flex items-center ">
                            <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                                onclick="return this.parentNode.parentNode.remove()">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="green">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- planning -->
                <div class="flex flex-col  gap-5 mt-5 p-5 rounded-xl bg-white shadow-md border border-slate-100 w-250">
                    <h1 class="text-2xl font-bold text-slate-700">Planning</h1>
                    <form class="w-50 flex items-center gap-5" action="Planing.php" method="POST">
                        <input type="hidden" name="id" id="id">
                        <div class="flex flex-col gap-2 ">
                            <label class="font-semibold text-slate-700 text-sm" for="plnning">Entraîneur: </label>
                            <input
                                class="border border-slate-300 rounded-xl p-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                                type="text" id="coach" name="coach" placeholder="entraineur">
                        </div>

                        <div class="flex flex-col gap-2 ">
                            <label class="font-semibold text-slate-700 text-sm" for="jour">Jour activité: </label>
                            <input
                                class="border border-slate-300 rounded-xl p-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                                type="text" id="jour" name="jour" placeholder="jour activite">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-semibold text-slate-700 text-sm" for="time">Heure activité:</label>
                            <input
                                class="border border-slate-300 rounded-xl p-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                                type="time" name="time" id="time">
                        </div>

                        <div class="flex flex-col gap-2">
                            <h1 class="font-semibold text-slate-700 text-sm">Activites: </h1>
                            <select
                                class="cursor-pointer border border-slate-300 rounded-lg w-30 p-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition bg-white"
                                name="activity" id="activity">
                                <?php foreach ($activites as $activiteRow): ?>
                                    <option value=<?= $activiteRow["Libelle_Activite"] ?>> <?= $activiteRow["Libelle_Activite"] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="flex justify-center items-center gap-2 mt-5.5">
                            <input
                                class="px-4.5 py-1 text-white text-lg bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition-colors duration-200 cursor-pointer"
                                type="submit" value="Ajouter" name="add">
                            <input
                                class="px-4.5 py-1 text-white text-lg bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200 cursor-pointer"
                                type="submit" value="Modifier" name="update">
                        </div>
                    </form>
                </div>

                <div class="flex flex-col gap-4 mt-5 w-full max-w-4xl mx-auto">
                    <?php $currentDay = ""; ?>
                    <?php foreach ($plannings as $row): ?>

                        <?php $day = trim(strtolower($row['jour'])); ?>

                        <?php if ($currentDay !== $day): ?>
                            <?php $currentDay = $day; ?>
                            <div class="mt-6 first:mt-0">
                                <h2
                                    class="bg-slate-800 text-white px-6 py-2 rounded-lg text-sm font-bold uppercase tracking-widest shadow-sm">
                                    <?= htmlspecialchars($currentDay) ?>
                                </h2>
                            </div>
                        <?php endif; ?>

                        <div
                            class="flex items-center justify-between bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow ">
                            <div class="flex items-center gap-3 w-40">
                                <img class="h-5" src="../assets/icons/time-outline.svg" alt="time">
                                <span class="font-bold text-slate-700">
                                    <?= date('H:i', strtotime($row['heureDebut'])) ?> -
                                    <?= date('H:i', strtotime($row['heureFin'])) ?>
                                </span>
                            </div>

                            <div class="flex-1 px-4">
                                <span
                                    class="bg-sky-100 text-sky-700 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                    <?= htmlspecialchars($row['activity_name']) ?>
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400 font-medium">ENTRAÎNEUR</p>
                                <p class="text-slate-600 font-semibold font-serif">
                                    <?= htmlspecialchars($row['Entraineur']) ?>
                                </p>
                            </div>

                            <div class="flex gap-2 item-center ml-20">
                                <button data-id="<?= $row["Id_Planning"] ?>" data-name="<?= $row['activity_name'] ?>"
                                    data-day="<?= $row['jour'] ?>" data-time="<?= $row['heureDebut'] ?>"
                                    data-coach="<?= $row['Entraineur'] ?>"
                                    class="editBtn cursor-pointer hover:scale-110 transition-transform duration-200">
                                    <img class="h-6 w-6" src="../assets/icons/svgviewer-output.svg" alt="modifier">
                                </button>

                                <a href="../../controller/suprimerPlanning.php?Id_Planning=<?= $row["Id_Planning"] ?>">
                                    <button
                                        class="suprimer cursor-pointer hover:scale-110 transition-transform duration-200">
                                        <img class="h-6.5 w-6.5 suprimer" src="../assets/icons/delete.svg" alt="suprimer">
                                    </button>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
    <script src="../assets/script/planingEdit.js"></script>
    <script src="../assets/script/deleteConfirmartion.js"></script>
</body>

</html>