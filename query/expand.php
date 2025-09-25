<?php
include('db_conn.php');

header('Content-Type: application/json');

try {
    $viewId = isset($_GET['view']) ? $_GET['view'] : '';
    $data = [];
    $columns = [];
    $sort = !empty($_GET['search']) ? $_GET['search'] : 'sticker_id';
    $EQSort = !empty($_GET['search']) ? $_GET['search'] : 'asset_tag';

    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    switch($viewId) {
        case 'DesktopsInSystem':
            $query = "SELECT * FROM DesktopsInSystem ORDER BY `Num_units` + 0 DESC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'LaptopsInSystem':
            $query = "SELECT * FROM LaptopsInSystem ORDER BY `Num_units` + 0 DESC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'MonitorsInSystem':
            $query = "SELECT * FROM MonitorsInSystem ORDER BY `Num_units` + 0 DESC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'PeripheralsInSystem':
            $query = "SELECT * FROM PeripheralsInSystem ORDER BY `Num_units` + 0 DESC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'stickertableResults':
            $query = "SELECT * FROM stickertableResults";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'TonerInSystem':
            $query = "SELECT * FROM TonerInSystem ORDER BY $sort ASC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'OpenEQ':
            $query = "SELECT * FROM OpenEQ ORDER BY $EQSort ASC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'PrintersInSystem':
            $query = "SELECT * FROM PrintersInSystem ORDER BY `Num_units` + 0 DESC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'MacsInSystem':
            $query = "SELECT * FROM MacsInSystem ORDER BY `Num_units` + 0 DESC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'LonerLaptopsView':
            $query = "SELECT * FROM LonerLaptopsView ORDER BY `asset_tag` ASC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'OrdersView':
            $query = "SELECT * FROM OrdersView ORDER BY `request_id` ASC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        default:
            throw new Exception("Invalid view ID");
    }
    
    if (count($data) > 0) {
        $columns = array_keys($data[0]);
    }

    echo json_encode([
        'success' => true,
        'columns' => $columns,
        'data' => $data
    ]);

} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>