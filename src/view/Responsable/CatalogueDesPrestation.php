<?php


include __DIR__ . '/dashData.php';
include __DIR__ . "../../../controller/typeAbonnementConfig.php";
include __DIR__ . "../../../controller/TypeActiviteController.php";
include __DIR__ . "../../../controller/assuranceConfig.php";
include __DIR__ . "../../../Modules/Connecter.php";
include __DIR__ . "../../../controller/ActiviteController.php";
include __DIR__ . "../../../controller/activiteTable.php";
include __DIR__ . "../../../controller/EntraineurTable.php";


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

    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="../assets/images/top-sport-noBack.png" />
    <link rel="stylesheet" href="../../../css/style.css">
    <title>Offres & Activités</title>
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


            <div class="flex items-center justify-around mt-auto border-t-2 border-blue-200">
                <?php foreach ($socials as $social): ?>
                    <li class="list-none ">
                        <a href="">
                            <img class="h-8 mt-10 transition delay-120 duration-150 ease-out hover:-translate-y-1 hover:scale-110"
                                src=<?= $social["url"] ?>
                                alt=<?= $social["alt"] ?>>
                        </a>
                    </li>
                <?php endforeach;  ?>
            </div>
        </div>

        <!-- Up bar -->

        <div class="flex flex-col justify-around mt-2 overflow-auto">

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
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:-translate-x-1"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 18 16">
                                <path stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                            </svg>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>

            <!-- form -->
            <div class="flex justify-around ">

                <!-- type abonnement -->
                <div class="flex flex-col gap-5 mt-5 p-5 rounded-xl bg-white shadow-md border border-slate-100 w-130">
                    <?php if (isset($error2)): ?>
                        <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
                            <span class="flex-1 text-sm font-medium"><?php echo $error2; ?></span>
                            <div class="ml-4 items-center flex">
                                <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                                    onclick="return this.parentNode.parentNode.remove()">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php elseif (isset($validation2)): ?>
                        <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-green-800 bg-green-100 border border-green-200 rounded-lg shadow-sm" role="alert">
                            <span class="text-green-600 font-semibold text-md text-center"><?php echo $validation2; ?></span>
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
                    <form class="w-55 flex items-end gap-5" action="CatalogueDesPrestation.php" method="POST">
                        <div class="flex flex-col gap-2 ">
                            <input type="hidden" name="id" id="id">
                            <label class="text-slate-700  text-2xl font-bold" for="type_abonnement">Type abonnement: </label>
                            <input class="border border-slate-300 rounded-xl p-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" type="text" id="type_abonnement" name="typeAbonnement">
                        </div>
                        <input class="px-4.5 py-1 text-white text-lg bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition-colors duration-200 cursor-pointer"
                            type="submit"
                            value="Ajouter"
                            name="add_type">

                        <input class="px-4.5 py-1 text-white text-lg bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200 cursor-pointer"
                            type="submit"
                            value="Modifier"
                            name="update_type">
                    </form>

                    <table class="table-auto w-full text-center border-collapse">
                        <thead class="bg-slate-50 border-b-2 border-slate-200">
                            <tr>
                                <td class="text-sm font-bold p-2 text-slate-700">Type abonnement</td>
                                <td class="text-sm font-bold p-2 text-slate-700">Action</td>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($typeAbonnement as $type): ?>
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="p-2 text-slate-700"><?= $type["Libelle_TAbonnement"] ?></td>
                                    <td class=" flex items-center justify-center gap-2 p-1">

                                        <button data-id="<?= $type["Id_TAbonnement"] ?>"
                                            data-name="<?= htmlspecialchars($type["Libelle_TAbonnement"]) ?>"
                                            class="editBtn cursor-pointer hover:scale-110 transition-transform duration-200">
                                            <img class="h-6 w-6" src="../assets/icons/svgviewer-output.svg" alt="modifier">
                                        </button>

                                        <a href="../../controller/suprimerTypeAbonnement.php?Id_TAbonnement=<?= $type["Id_TAbonnement"] ?>">
                                            <button class="suprimer cursor-pointer hover:scale-110 transition-transform duration-200">
                                                <img class="h-7 w-7 suprimer" src="../assets/icons/delete.svg" alt="suprimer">
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach;  ?>
                        </tbody>
                    </table>
                </div>

                <!--  assurance  -->

                <div class="flex flex-col gap-5 mt-5 p-5 rounded-xl bg-white shadow-md border border-slate-100 w-160">

                    <?php if (isset($error3)): ?>
                        <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
                            <span class="flex-1 text-sm font-medium"><?php echo $error3; ?></span>
                            <div class="ml-4 items-center flex">
                                <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                                    onclick="return this.parentNode.parentNode.remove()">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                         clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php elseif (isset($validation3)): ?>
                        <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-green-800 bg-green-100 border border-green-200 rounded-lg shadow-sm" role="alert">
                            <span class="text-green-600 font-semibold text-md text-center"><?php echo $validation3; ?></span>
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
                    <form class="w-55 flex items-end gap-5" action="CatalogueDesPrestation.php" method="POST">

                        <div class="flex flex-col gap-2 ">
                            <input type="hidden" name="id2" id="id2">
                            <h1 class=" text-2xl font-bold text-slate-700">Assurance</h1>
                            <label class="font-semibold text-slate-700 text-sm" for="assurance">Date de début: </label>
                            <input class="border border-slate-300 rounded-xl p-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" type="date" id="dateDEbut" name="dateDebut">
                        </div>

                        <div class="flex flex-col gap-2 mt-9">
                            <label class="font-semibold text-slate-700 text-sm" for="prix">Prix: </label>
                            <input class="border border-slate-300 rounded-xl p-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" type="number" id="prix" name="prix">
                        </div>

                        <input class="px-4.5 py-1 text-white text-lg bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition-colors duration-200 cursor-pointer"
                            type="submit"
                            value="Ajouter"
                            name="add">

                        <input class="px-4.5 py-1 text-white text-lg bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200 cursor-pointer"
                            type="submit"
                            value="Modifier"
                            name="update">

                    </form>
                    <table class="table-auto w-full text-center border-collapse">
                        <thead class="bg-slate-50 border-b-2 border-slate-200">
                            <tr>
                                <td class="text-sm font-bold p-2 text-slate-700">Date de début</td>
                                <td class="text-sm font-bold p-2 text-slate-700">Date de fin</td>
                                <td class="text-sm font-bold p-2 text-slate-700">Prix</td>
                                <td class="text-sm font-bold p-2 text-slate-700">Action</td>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($assurances as $assurance): ?>
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="p-2 font-medium text-slate-800"><?= (new DateTime($assurance["DateDebut"]))->format('d-m-Y')  ?></td>
                                    <td class="p-2 font-medium text-slate-800"><?= (new DateTime($assurance["DateFin"]))->format('d-m-Y')  ?></td>
                                    <td class="p-2 text-slate-600"><?= $assurance["Prix"] . ' Dh' ?></td>
                                    <td class="flex items-center justify-center gap-2 p-1">

                                        <button data-id="<?= $assurance["Id_Assurance"] ?>"
                                            data-date="<?= $assurance["DateDebut"] ?>"
                                            data-prix="<?= $assurance["Prix"] ?>"
                                            class="editBtn2 cursor-pointer hover:scale-110 transition-transform duration-200">
                                            <img class="h-6 w-6" src="../assets/icons/svgviewer-output.svg" alt="modifier">
                                        </button>

                                        <a href="../../controller/suprimerAssurance.php?Id_Assurance=<?= $assurance["Id_Assurance"] ?>">
                                            <button class="suprimer cursor-pointer hover:scale-110 transition-transform duration-200">
                                                <img class="h-7 w-7 suprimer" src="../assets/icons/delete.svg" alt="suprimer">
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach;  ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-row-reverse justify-around">

                <!-- activite -->

                <div class="flex flex-col gap-5 mt-5 p-5 rounded-xl bg-white shadow-md border border-slate-100 w-160">
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
                    <form class="w-55 flex items-end gap-5" action="CatalogueDesPrestation.php" method="POST">
                        <div class="flex flex-col gap-2">
                            <input type="hidden" name="id" id="id3">
                            <label class="text-slate-700  text-2xl font-bold" for="activite">Activité: </label>
                            <input class="border border-slate-300 rounded-xl p-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" type="text" id="activite" name="activite">
                        </div>

                        <div class="flex flex-col gap-2">
                            <h1 class="font-semibold text-slate-700 text-sm">Entraîneurs: </h1>
                            <select id="entraineur" name="entraineur" class="cursor-pointer border border-slate-300 rounded-lg w-30 p-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition bg-white">
                                <?php foreach ($entraineurs as $entraineur): ?>
                                    <option value="<?= $entraineur["Prenom"] ?>"><?= $entraineur["Prenom"] ?></option>
                                <?php endforeach;  ?>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <h1 class="font-semibold text-slate-700 text-sm">Assurances: </h1>
                            <select id="assu" name="assu" class="cursor-pointer border border-slate-300 rounded-lg w-30 p-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition bg-white">
                                <?php foreach ($ass as $as): ?>
                                    <option value="<?= $as["Prix"] ?>"><?= $as["Prix"] ?></option>
                                <?php endforeach;  ?>
                            </select>
                        </div>

                        <div class="flex flex-col-reverse gap-2.5">
                            <input class="px-4.5 py-1 text-white text-lg bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition-colors duration-200 cursor-pointer"
                                type="submit"
                                value="Ajouter"
                                name="add_act">

                            <input class="px-4.5 py-1 text-white text-lg bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200 cursor-pointer"
                                type="submit"
                                value="Modifier"
                                name="update_act">
                        </div>
                    </form>

                    <table class="table-auto w-full text-center border-collapse">
                        <thead class="bg-slate-50 border-b-2 border-slate-200">
                            <tr>
                                <td class="p-2 text-sm font-bold text-slate-700">Activité</td>
                                <td class="p-2 text-sm font-bold text-slate-700">Assurance prix</td>
                                <td class="p-2 text-sm font-bold text-slate-700">Entraîneur</td>
                                <td class="p-2 text-sm font-bold text-slate-700">Responsable</td>
                                <td class="p-2 text-sm font-bold text-slate-700">Action</td>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($activites as $activite): ?>
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="p-2 font-medium text-slate-800"><?= $activite["Libelle_Activite"] ?></td>
                                    <td class="p-2 text-slate-600"><?= $activite["assurance_prix"] . ' Dh' ?></td>
                                    <td class="p-2 text-slate-600"><?= $activite["entraineur_prenom"] ?></td>
                                    <td class="p-2 text-slate-600"><?= $activite["responsable_prenom"] ?></td>
                                    <td class="flex items-center justify-center gap-3 p-2">

                                        <button data-id="<?= $activite["Id_Activite"] ?>"
                                            data-name="<?= $activite["Libelle_Activite"] ?>"
                                            data-ass="<?= $activite["assurance_prix"] ?>"
                                            data-entr="<?= $activite["entraineur_prenom"] ?>"
                                            class="editBtn3 cursor-pointer hover:scale-110 transition-transform duration-200">
                                            <img class="h-6 w-6" src="../assets/icons/svgviewer-output.svg" alt="modifier">
                                        </button>

                                        <a href="../../controller/suprimerActivite.php?Id_Activite=<?= $activite["Id_Activite"] ?>">
                                            <button class="suprimer cursor-pointer hover:scale-110 transition-transform duration-200">
                                                <img class="h-7 w-7 suprimer" src="../assets/icons/delete.svg" alt="suprimer">
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach;  ?>
                        </tbody>
                    </table>
                </div>


                <!-- type activite -->

                <div class="flex flex-col gap-5 mt-5 p-5 rounded-xl bg-white shadow-md border border-slate-100 w-130">

                    <?php if (isset($error4)): ?>
                        <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-red-800 bg-red-100 border border-red-200 rounded-lg shadow-sm" role="alert">
                            <span class="flex-1 text-sm font-medium"><?php echo $error4; ?></span>
                            <div class="ml-4 items-center flex">
                                <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                                    onclick="return this.parentNode.parentNode.remove()">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php elseif (isset($validation4)): ?>
                        <div class="flex items-center justify-between w-full max-w-sm gap-3 p-3 mt-2 text-green-800 bg-green-100 border border-green-200 rounded-lg shadow-sm" role="alert">
                            <span class="text-green-600 font-semibold text-md text-center"><?php echo $validation4; ?></span>
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
                    <form class="w-50 flex items-end gap-5" action="CatalogueDesPrestation.php" method="POST">

                        <div class="flex flex-col gap-2">
                            <input type="hidden" name="id4" id="id4">
                            <label class="text-slate-700  text-2xl font-bold" for="typeActivite">Type d'activité: </label>
                            <input class="border border-slate-300 rounded-xl p-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" type="text" id="typeActivite" name="typeActivte">
                        </div>

                        <div class="flex flex-col gap-2">
                            <h1 class="font-semibold text-slate-700 text-sm">Activité: </h1>
                            <select id="act" name="act" class="cursor-pointer border border-slate-300 rounded-lg w-30 p-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition bg-white">
                                <?php foreach ($activites as $act): ?>
                                    <option value="<?= $act["Libelle_Activite"] ?>"> <?= $act["Libelle_Activite"] ?></option>
                                <?php endforeach;  ?>
                            </select>
                        </div>

                        <div class="flex flex-col-reverse gap-2.5">
                            <input class=" px-4.5 py-1  text-white text-lg bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition-colors duration-200 cursor-pointer"
                                type="submit"
                                value="Ajouter"
                                name="addTypeActivite">

                            <input class="px-4.5 py-1 text-white text-lg bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition-colors duration-200 cursor-pointer"
                                type="submit"
                                value="Modifier"
                                name="updateTypeActivite">
                        </div>

                    </form>

                    <table class="table-auto w-full text-center border-collapse">
                        <thead class="bg-slate-50 border-b-2 border-slate-200">
                            <tr>
                                <td class="text-sm font-bold p-2 text-slate-700">Type d'activité</td>
                                <td class="text-sm font-bold p-2 text-slate-700">Activité</td>
                                <td class="text-sm font-bold p-2 text-slate-700">Action</td>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($types as $type): ?>
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="p-2 font-medium text-slate-800"><?= $type["Libelle_TActivite"] ?></td>
                                    <td class="p-2 text-slate-600"><?= $type["activite_nom"] ?></td>
                                    <td class="flex items-center justify-center gap-3 p-2">

                                        <button data-id="<?= $type["Id_TActivite"] ?>"
                                            data-name="<?= $type["Libelle_TActivite"] ?>"
                                            data-Acti="<?= $type["activite_nom"] ?>"
                                            class="editBtn4 cursor-pointer hover:scale-110 transition-transform duration-200">
                                            <img class="h-6 w-6" src="../assets/icons/svgviewer-output.svg" alt="modifier">
                                        </button>

                                        <a href="">
                                            <button class="suprimer cursor-pointer hover:scale-110 transition-transform duration-200">
                                                <img class="h-6.5 w-6.5  suprimer" src="../assets/icons/delete.svg" alt="suprimer">
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach;  ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/script/typAbonnementEdit.js"></script>
    <script src="../assets/script/assuranceEdite.js"></script>
    <script src="../assets/script/deleteConfirmartion.js"></script>
    <script src="../assets/script/activiteEdit.js"></script>
    <script src="../assets/script/typeActiviteEdite.js"></script>
</body>

</html>