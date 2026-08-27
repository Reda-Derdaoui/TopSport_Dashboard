<?php
session_start();

include __DIR__ . "/dashData.php";
include __DIR__ . "../../../Modules/Connecter.php";

if (!isset($_SESSION['userName']) || !isset($_SESSION['logged_in'])) {

    header("Location: ../../view/login.php");
    exit();
}

if (isset($_SESSION["user_id"])) {
    $idRes = $_SESSION["user_id"];
} else {
    echo "Responsable not found";
}


$currentPage = basename($_SERVER['PHP_SELF']);


// --- Activities Block ---
$activites = [];
$data = [];

try {
    $req = "SELECT 
    activite.Libelle_Activite, 
    COUNT(participer.id) AS total
    FROM participer
    INNER JOIN activite ON participer.Id_Activite = activite.Id_Activite
    GROUP BY activite.Libelle_Activite";

    $result = $pdo->query($req);

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch()) {
            $activites[] = $row["Libelle_Activite"];
            $data[] = $row['total'];
        }
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

// total Adherents
try {
    $req2 = "SELECT COUNT(*) AS total_adherents FROM adherent ad WHERE ad.Res_id = ?";
    $pst = $pdo->prepare($req2);
    $params = [$idRes];
    $pst->execute($params);
    $result = $pst->fetch();
    $totalAdherents = $result["total_adherents"];
} catch (Exception $ex) {
    echo $ex->getMessage();
}

$adherents = [];
try {
    $sql = "SELECT 
        p.Prenom, 
        p.Nom,
        ad.id AS Id_adherent,
        ab.Id_Adherent,
        ab.DateFin
    FROM personne p
    JOIN adherent ad ON p.id = ad.id
    JOIN abonnement ab ON ab.Id_Adherent = ad.id
    WHERE ab.DateFin <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    AND ad.Res_id = ?
    ORDER BY ab.DateFin ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idRes]);

    $adherents = $stmt->fetchAll();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
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
    <title>Home</title>
</head>

<body>
    <div class="flex gap-10 h-screen bg-blue-100 ">

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


        <div class="flex flex-col gap-5 mt-2 mr-5 overflow-auto">
            <!-- Up bar -->
            <div class="flex justify-between items-center bg-gray-100 gap-5 w-300 p-5 rounded-xl ">
                <div class="flex justify-center items-center gap-3">
                    <h1 class="text-xl text-blue-700 font-bold">TOP SPORT</h1>
                </div>

                <div class="flex justify-center items-center gap-5 ">
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

            <!-- Charts  -->
            <div class="bg-white p-6 border border-gray-100 rounded-2xl 
                    shadow-sm hover:shadow-md transition-shadow w-300 overflow-auto h-80">
                <h2 class="text-xl font-bold mb-3">
                    Expire bientôt
                </h2>

                <div class="space-y-3">
                    <?php $today = new DateTime('today'); ?>
                    <?php $soonDate = new DateTime('today + 7 days'); ?>

                    <?php foreach ($adherents as $adherent): ?>
                        <?php $dateFin = new DateTime($adherent["DateFin"]) ?>

                        <div class="flex items-center justify-between border-b pb-3">
                            <div>
                                <p class="font-semibold">
                                    <?= htmlspecialchars($adherent["Prenom"]) ?>
                                    <?= htmlspecialchars($adherent["Nom"]) ?>
                                </p>

                                <p class="text-sm text-gray-500">
                                    Expire le :
                                    <?= (new DateTime($adherent["DateFin"]))->format('d-m-Y') ?>
                                </p>
                            </div>

                            <div class="flex gap-5 items-center">
                                <?php if ($dateFin->format("Y/m/d") < $today->format("Y/m/d")): ?>

                                    <span class="bg-red-600 text-red-100 text-sm px-3 py-1 rounded-full">
                                        Expiré
                                    </span>

                                <?php elseif ($dateFin->format("Y/m/d") <= $soonDate->format("Y/m/d")): ?>

                                    <span class="bg-yellow-100 text-yellow-600 text-sm px-3 py-1 rounded-full">
                                        Bientôt
                                    </span>

                                <?php endif; ?>

                                <button class="inline-flex text-white transition ease-in-out duration-150 cursor-pointer"
                                    onclick="return this.parentNode.parentNode.remove()">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="red">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1
                                             1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex justify-between gap-5 mt-5 mb-5 mr-5">
                <div class="flex flex-col items-center justify-center gap-4 bg-white border border-gray-100 rounded-2xl 
                             shadow-sm hover:shadow-md transition-shadow w-135 h-70 p-8 text-center">
                    <div class="bg-linear-to-br from-blue-500 to-indigo-600 p-4 rounded-2xl shadow-lg shadow-blue-200">
                        <img class="h-12 w-12 invert brightness-0" src="../assets/icons/people-outline.svg" alt="people">
                    </div>
                    <div class="mt-2">
                        <p class="text-5xl font-black text-gray-900 tracking-tight">
                            <?= $totalAdherents ?>
                        </p>
                        <h2 class="text-gray-500 font-medium uppercase tracking-widest text-xs mt-3">Total Adhérents</h2>
                    </div>
                </div>
                <div style="width: 600px; height: auto;" class="bg-gray-100  rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <canvas id="myChart"></canvas>
                </div>
            </div>


        </div>
    </div>

    <!-- Chart.js scripts -->
    <script src="../assets/script/chart.umd.min.js"></script>
    <script>
        // === ACTIVITES CHART ===
        const activites = <?= json_encode($activites) ?>;
        const dataAct = <?= json_encode($data) ?>;

        const backgroundColors = [
            'rgba(54, 162, 235, 0.8)', // Blue
            'rgba(255, 99, 132, 0.8)', // Pink/Red
            'rgba(255, 206, 86, 0.8)', // Yellow
            'rgba(75, 192, 192, 0.8)', // Teal
            'rgba(153, 102, 255, 0.8)', // Purple
            'rgba(255, 159, 64, 0.8)' // Orange
        ];

        const borderColors = backgroundColors.map(color => color.replace('0.8', '1'));

        const data = {
            labels: activites,
            datasets: [{
                label: 'Membres par activité',
                data: dataAct,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1.5,
                borderRadius: 5,
                barThickness: 45,
                borderSkipped: false,
                hoverBackgroundColor: borderColors
            }]
        };

        const config = {
            type: 'bar',
            data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            padding: 20,
                            font: {
                                family: "'Inter', 'Helvetica Neue', 'Arial', sans-serif",
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#333'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleFont: {
                            size: 14,
                            family: "'Inter', sans-serif"
                        },
                        bodyFont: {
                            size: 13,
                            family: "'Inter', sans-serif"
                        },
                        padding: 12,
                        cornerRadius: 6,
                        displayColors: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: {
                            display: false
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 10,
                            color: '#6b7280',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        };

        // Render Activites Chart
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
</body>

</html>