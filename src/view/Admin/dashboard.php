<?php
session_start();

include __DIR__ . "/dashData.php";
include __DIR__ . "../../../Modules/Connecter.php";

if (!isset($_SESSION['userName']) || !isset($_SESSION['logged_in'])) {

    header("Location: ../../view/login.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);

// --- Activities Block ---
$activites = [];
$data = [];

try {
    $req = "SELECT activite.Libelle_Activite, COUNT(participer.id) AS total
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

// --- Revenue Block ---
$labels = [];
$revenues = [];

try {
    $sql = "SELECT DATE_FORMAT(DateDebut, '%b') AS month, SUM(Prix) AS revenue
    FROM abonnement
    GROUP BY MONTH(DateDebut)
    ORDER BY MONTH(DateDebut)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $revenueRows = $stmt->fetchAll();

    foreach ($revenueRows as $row) {
        $labels[] = $row['month'];
        $revenues[] = $row['revenue'];
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

// total Adherents
try {
    $req2 = "SELECT COUNT(*) AS total_adherents FROM adherent";
    $pst = $pdo->query($req2);
    $result = $pst->fetch();
    $totalAdherents = $result["total_adherents"];
} catch (Exception $ex) {
    echo $ex->getMessage();
}


//revenu par activity
$revenueByActivity = [];
try {

    $sql = "SELECT 
    a.Libelle_Activite AS activity,
    DATE_FORMAT(ab.DateDebut, '%b') AS month,
    MONTH(ab.DateDebut) AS month_number,
    SUM(ab.Prix) AS revenue
    FROM abonnement ab JOIN participer p ON p.id = ab.Id_Adherent
    JOIN activite a ON a.Id_Activite = p.Id_Activite
    GROUP BY a.Libelle_Activite, MONTH(ab.DateDebut),
    DATE_FORMAT(ab.DateDebut, '%b')
    ORDER BY month_number";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $activity = $row['activity'];
        if (!isset($revenueByActivity[$activity])) {
            $revenueByActivity[$activity] = [
                'labels' => [],
                'revenues' => []
            ];
        }

        $revenueByActivity[$activity]['labels'][] = $row['month'];
        $revenueByActivity[$activity]['revenues'][] = $row['revenue'];
    }
} catch (Exception $ex) {

    echo $ex->getMessage();
}


$currentDate = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../assets/images/top-sport-noBack.png" />
    <link rel="stylesheet" href="../../../css/style.css">
    <title>Home</title>
</head>

<body>
    <div class="flex h-screen bg-blue-100 overflow-hidden">

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

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col gap-6 p-6 overflow-y-auto w-full
        max-xl:gap-5 max-xl:p-5
        max-lg:gap-4 max-lg:p-4
        max-md:gap-4 max-md:p-3
        max-sm:gap-3 max-sm:p-2">

            <!-- Up bar -->
            <div class="flex justify-between items-center bg-gray-100 w-full p-5 rounded-xl shadow-sm
            max-xl:p-4
            max-lg:p-3
            max-md:pl-16 
            max-sm:p-2 max-sm:pl-14">

                <div class="flex justify-center items-center gap-3">
                    <h1 class="text-xl text-blue-700 font-bold max-sm:text-lg tracking-wide">
                        TOP SPORT
                    </h1>
                </div>

                <div class="flex justify-center items-center">
                    <form action="../../controller/logoutController.php" method="POST">
                        <button type="submit" name="logout" class="cursor-pointer group flex items-center justify-center gap-2
                        px-5 py-2.5 text-sm font-medium text-white
                        transition-all duration-300 ease-in-out
                        bg-linear-to-r from-red-500 to-red-600
                        rounded-lg shadow-md shadow-red-500/30
                        hover:from-red-600 hover:to-red-700
                        hover:shadow-lg hover:-translate-y-0.5
                        focus:ring-4 focus:ring-red-500/50 focus:outline-none
                        max-md:px-3 max-md:py-2 max-sm:text-xs max-sm:gap-1">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 transition-transform duration-300 group-hover:-translate-x-1 max-sm:w-5 max-sm:h-5"
                                viewBox="0 0 512 512">
                                <path
                                    d="M304 336v40a40 40 0 01-40 40H104a40 40 0 01-40-40V136a40 40 0 0140-40h152c22.09 0 48 17.91 48 40v40M368 336l80-80-80-80M176 256h256"
                                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="32" />
                            </svg>
                            <span class="max-sm:hidden">Se déconnecter</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Charts Row -->
            <!-- flex-1 is used here instead of fixed widths, making it scale perfectly -->
            <div class="grid grid-cols-2 max-md:grid-cols-1 w-full gap-6 max-xl:gap-5 max-lg:gap-4 max-md:gap-4">

                <!-- Chart 1 -->
                <div
                    class="w-full bg-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 relative h-75 max-sm:h-62.5 max-sm:p-2">
                    <canvas id="revenuChart" class="w-full h-full"></canvas>
                </div>

                <!-- Chart 2 -->
                <div
                    class="w-full bg-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 relative h-75 max-sm:h-62.5 max-sm:p-2">
                    <canvas id="revenueActivityChart" class="w-full h-full"></canvas>
                </div>

            </div>
            <!-- Total adherents + chart -->
            <div
                class="flex w-full gap-6 pb-6 max-xl:gap-5 max-lg:gap-4 max-md:flex-col max-md:gap-4 max-md:pb-4 max-sm:pb-2">

                <!-- Total adherents Box -->
                <div class="flex-1 w-full flex flex-col items-center justify-center gap-4
                bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow
                p-8 max-xl:p-6 max-lg:p-4 max-md:py-8 max-sm:py-6">

                    <div
                        class="bg-linear-to-br from-blue-500 to-indigo-600 p-4 rounded-2xl shadow-lg shadow-blue-200 max-sm:p-3">
                        <img class="h-12 w-12 invert brightness-0 max-sm:h-10 max-sm:w-10"
                            src="../assets/icons/people-outline.svg" alt="people">
                    </div>

                    <div class="mt-2 text-center">
                        <p class="text-5xl font-black text-gray-900 tracking-tight max-lg:text-4xl max-sm:text-3xl">
                            <?= $totalAdherents ?>
                        </p>
                        <h2 class="text-gray-500 font-medium uppercase tracking-widest text-xs mt-3 max-sm:text-[10px]">
                            Total Adhérents
                        </h2>
                    </div>
                </div>

                <!-- Bottom Chart Box -->
                <div
                    class="flex-1 w-full bg-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 relative min-h-75 max-sm:min-h-62.5 max-sm:p-2">
                    <canvas id="myChart"></canvas>
                </div>

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


    <!-- Chart.js scripts -->
    <script src="../assets/script/chart.umd.min.js"></script>

    <script>
        // === REVENUE CHART === 
        const data2 = {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Revenue (MAD)',
                data: <?= json_encode($revenues) ?>,
                backgroundColor: '#6366f1',
                borderRadius: 5,
                barThickness: 45,
                borderSkipped: false
            }]
        };

        const config2 = {
            type: 'bar',
            data: data2,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };

        const revenuChart = new Chart(
            document.getElementById('revenuChart'),
            config2
        );


        // === ACTIVITES CHART ===
        const activites = <?= json_encode($activites) ?>;
        const dataAct = <?= json_encode($data) ?>;

        const backgroundColors = [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)'
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
            type: 'doughnut',
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


        //====== activity revenu =======
        const revenueActivitiesMonthsData = <?= json_encode($revenueByActivity) ?>;

        // Get all months
        const revenueMonthsLabels = [...new Set(
            Object.values(revenueActivitiesMonthsData)
                .flatMap(item => item.labels)
        )];

        const revenueMonthsBgColors = [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)'
        ];

        const revenueMonthsBorderColors =
            revenueMonthsBgColors.map(color =>
                color.replace('0.8', '1')
            );

        // Create datasets
        const revenueMonthsDatasets =
            Object.keys(revenueActivitiesMonthsData)
                .map((activity, index) => {
                    const revenues = revenueMonthsLabels.map(month => {
                        const monthIndex =
                            revenueActivitiesMonthsData[activity]
                                .labels.indexOf(month);

                        return monthIndex !== -1 ?
                            revenueActivitiesMonthsData[activity]
                                .revenues[monthIndex] :
                            0;
                    });

                    return {
                        label: activity,
                        data: revenues,
                        backgroundColor: revenueMonthsBgColors[index % revenueMonthsBgColors.length],
                        borderColor: revenueMonthsBorderColors[index % revenueMonthsBorderColors.length],
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 30,
                        borderSkipped: false
                    };
                });

        const revenueMonthsChartData = {
            labels: revenueMonthsLabels,
            datasets: revenueMonthsDatasets
        };

        const revenueMonthsChartConfig = {
            type: 'bar',
            data: revenueMonthsChartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',

                        labels: {
                            color: '#374151',

                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.raw + ' DH';
                            }
                        }
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        border: {
                            display: false
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            display: false
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
                }
            }
        };

        new Chart(
            document.getElementById('revenueActivityChart'),
            revenueMonthsChartConfig
        );
    </script>

</body>

</html>