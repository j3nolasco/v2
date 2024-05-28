<?php

$path =__DIR__ . '/Data.json';
$jsonString = file_get_contents($path);
$data = json_decode($jsonString, true);
$reportEntries = $data['Report_Entry'];

// Enable Error Reporting.
error_reporting(E_ALL);
ini_set('display_errors', 1);

//session_start();

// Constants for API Interaction.

const ITEMS_PER_PAGE = 25;

// function getToken()
// {
//     $postData = [
//         'grant_type' => GRANT_TYPE,
//         'refresh_token' => REFRESH_TOKEN,
//         'client_id' => CLIENT_ID,
//         'client_secret' => CLIENT_SECRET
//     ];

//     $curl = curl_init(TOKEN_ENDPOINT);
//     curl_setopt_array($curl, [
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_FAILONERROR => true,
//         CURLOPT_USERAGENT => 'cURL Request',
//         CURLOPT_POST => true,
//         CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
//         CURLOPT_POSTFIELDS => http_build_query($postData)
//     ]);

//     $response = curl_exec($curl);
//     $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//     if ($response === false || $statusCode >= 400) {
//         $error = curl_error($curl);
//         curl_close($curl);
//         throw new Exception('Failed to Retrieve Access Token. Error: ' . $error);
//     }

//    $data = json_decode($response, true);
   
//     curl_close($curl);

//     if (!isset($data['access_token'])) {
//         throw new Exception('Access token not found in response. Response: ' . $response);
//     }
//     return $data['access_token'];
// }

// try {
//     $authToken = getToken();
//     $curl = curl_init(BASE_URL);
//     curl_setopt_array($curl, [
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $authToken]
//     ]);
//     $response = curl_exec($curl);
//     if ($response === false) {
//         $error = curl_error($curl);
//         curl_close($curl);
//         throw new Exception('Failed to Make API Call. Error: ' . $error);
//     }
//     curl_close($curl);

//     $data = json_decode($response, true);
//     if (!isset($data['Report_Entry'])) {
//         throw new Exception('Report Entry not found in response. Response: ' . $response);
//     }

//     $reportEntries = $data['Report_Entry'] ?? [];
// } catch (Exception $e) {
//     die('Error: ' . $e->getMessage());
/// }

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$totalItems = count($reportEntries);
$totalPages = ceil($totalItems / ITEMS_PER_PAGE);
$offset = ($page - 1) * ITEMS_PER_PAGE;
$currentEntries = array_slice($reportEntries, $offset, ITEMS_PER_PAGE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NWACC | Course Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .sticky-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid w-100 m-0 p-0">
        <div class="row w-100 m-0 p-0">
            <div class="col-md-3 text-white m-0 p-0 sticky-sidebar" style="background-color: #54815d;">
                <h1 class="text-center fw-bold fs-4">NWACC <span class="fw-light text-uppercase">Course Catalog</span></h1>
                <div class="list-group list-group-flush p-2">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by Course, Instructor, Delivery Mode, etc.">
                    <button class="btn btn-secondary mt-2" onclick="applyFilters()">Search</button>
                </div>
                <div class="list-group list-group-flush p-2">
                    <h2 class="text-left fw-bold fs-5">Academic Period</h2>
                    <select class="form-select" id="academicPeriodFilter" onchange="applyFilters()">
                        <option value="" class="text-muted" selected>Select an Academic Period</option>
                        <?php foreach (array_unique(array_column($reportEntries, 'Academic_Period')) as $period) {
                            echo "<option value=\"$period\">$period</option>";
                        } ?>
                    </select>
                </div>
                <div class="list-group list-group-flush p-2">
                    <h2 class="text-left fw-bold fs-5">Delivery Mode</h2>
                    <select class="form-select" id="deliveryModeFilter" onchange="applyFilters()">
                        <option value="" class="text-muted" selected>Select a Delivery Mode</option>
                        <?php foreach (array_unique(array_column($reportEntries, 'Delivery_Mode')) as $mode) {
                            echo "<option value=\"$mode\">$mode</option>";
                        } ?>
                    </select>
                </div>
                <div class="list-group list-group-flush p-2">
                    <h2 class="text-left fw-bold fs-5">Academic Level</h2>
                    <select class="form-select" id="academicLevelFilter" onchange="applyFilters()">
                        <option value="" class="text-muted" selected>Select an Academic Level</option>
                        <?php foreach (array_unique(array_column($reportEntries, 'Academic_Level')) as $level) {
                            echo "<option value=\"$level\">$level</option>";
                        } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-9 bg-light m-0 p-0">
                <div class="row w-100 m-0 p-0">
                    <div class="col-md-12 m-0 p-0">
                        <h1 class="text-left fw-bold fs-3 text-uppercase ms-3">Courses</h1>
                        <div class="row w-100 m-0 p-0" id="coursesList">
                            <?php foreach ($currentEntries as $course) : ?>
                                <div class="card m-2 p-2 w-100">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold"><?= htmlspecialchars($course['Course_Listing']) ?></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($course['Course_Subjects']) ?> | <?= htmlspecialchars($course['Delivery_Mode']) ?></h6>
                                        <p class="card-text"><strong>Instructor(s):</strong> <?= htmlspecialchars($course['Instructors'] ?? 'Not listed') ?></p>
                                        <p class="card-text"><strong>Format:</strong> <?= htmlspecialchars($course['Instructional_Format']) ?></p>
                                        <p class="card-text"><strong>Period:</strong> <?= htmlspecialchars($course['Academic_Period']) ?></p>
                                        <p class="card-text"><strong>Dates:</strong> <?= htmlspecialchars($course['Start_Date']) ?> to <?= htmlspecialchars($course['End_Date']) ?></p>
                                        <p class="card-text"><strong>Capacity:</strong> <?= htmlspecialchars($course['Section_Capacity']) ?> students</p>
                                        <p class="card-text"><strong>Units:</strong> <?= htmlspecialchars($course['Units_and_Unit_Type']) ?></p>
                                        <p class="card-text"><strong>Level:</strong> <?= htmlspecialchars($course['Academic_Level']) ?></p>
                                        <?php if (isset($course['Campus_Locations'])) : ?>
                                            <p class="card-text"><strong>Location:</strong> <?= htmlspecialchars($course['Campus_Locations']) ?></p>
                                        <?php endif; ?>
                                        <?php if (isset($course['Requirements'])) : ?>
                                            <p class="card-text"><strong>Requirements:</strong> <?= htmlspecialchars($course['Requirements']) ?></p>
                                        <?php endif; ?>

                                        <div class="card-text" style="text-align: justify;"><strong>Course Description:</strong> <?= $course['Course_Description'] ?></div>

                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <input type="number" class="form-control page-input" value="<?= $page ?>" min="1" max="<?= $totalPages ?>" onkeypress="goToPage(event)">
                                </li>
                                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        const $ = (selector) => document.querySelector(selector);

        const applyFilters = () => {
            const searchInput = $("#searchInput").value.toLowerCase();
            const academicPeriodFilter = $("#academicPeriodFilter").value;
            const deliveryModeFilter = $("#deliveryModeFilter").value;
            const academicLevelFilter = $("#academicLevelFilter").value;

            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('search', searchInput);
            urlParams.set('period', academicPeriodFilter);
            urlParams.set('mode', deliveryModeFilter);
            urlParams.set('level', academicLevelFilter);

            window.location.search = urlParams.toString();
        };

        const goToPage = (event) => {
            if (event.key === 'Enter') {
                const pageInput = event.target.value;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('page', pageInput);
                window.location.search = urlParams.toString();
            }
        };
    </script>
</body>

</html>