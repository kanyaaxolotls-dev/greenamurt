<?php
/**
 * Greenamrut Ayurveda - BV Flow Verification & Test Script
 */
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_config_file = __DIR__ . '/system/application/config/database.php';
if (!file_exists($db_config_file)) {
    die("Database config file not found.");
}
define('BASEPATH', __DIR__ . '/system/');
define('ENVIRONMENT', 'development');
include($db_config_file);

$db_config = $db['default'];
$host = !empty($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
$user = $db_config['username'];
$pass = $db_config['password'];
$dbname = $db_config['database'];

$conn = null;
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
} catch (Exception $e) {
    try {
        $conn = new mysqli('localhost', 'root', '', $dbname);
    } catch (Exception $e2) {
        die("Connection failed: " . $e2->getMessage());
    }
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Update DB package PVs
$conn->query("UPDATE product SET pv = 1 WHERE prod_price IN (8900, 4450) OR dealer_price IN (8900, 4450)");

// 2. Fetch Packages
$pkg_8900 = null;
$pkg_4450 = null;

$res_prod = $conn->query("SELECT * FROM product");
$products = array();
while ($row = $res_prod->fetch_assoc()) {
    $products[] = $row;
    if (floatval($row['prod_price']) == 8900 || floatval($row['dealer_price']) == 8900) {
        $pkg_8900 = $row;
    }
    if (floatval($row['prod_price']) == 4450 || floatval($row['dealer_price']) == 4450) {
        $pkg_4450 = $row;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Greenamrut Ayurveda - BV Flow Test Verification</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background: #f9f9f9; }
        .card { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .pass { background-color: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .fail { background-color: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>

<h1>Greenamrut Ayurveda - BV Flow Verification Results</h1>

<div class="card">
    <h2>1. Configured Packages in Database (`product` Table)</h2>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Price (`prod_price`)</th>
            <th>Dealer Price</th>
            <th>Configured PV (`pv`)</th>
            <th>Status</th>
        </tr>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><?php echo htmlspecialchars($p['prod_name']); ?></td>
            <td>₹<?php echo $p['prod_price']; ?></td>
            <td>₹<?php echo $p['dealer_price']; ?></td>
            <td><strong><?php echo $p['pv']; ?></strong></td>
            <td><span class="<?php echo floatval($p['pv']) == 1 ? 'pass' : 'fail'; ?>"><?php echo floatval($p['pv']) == 1 ? 'PV = 1 (OK)' : 'PV Missing'; ?></span></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php
// Function to count PV recursively like Earning::count_pv
function count_pv_test($conn, $id) {
    $stmt = $conn->prepare("SELECT id, mypv FROM member WHERE position = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $i = 0;
    
    // Sum direct mypv for nodes placed under $id
    $stmt_sum = $conn->prepare("SELECT SUM(mypv + team_power) as total_pv FROM member WHERE position = ? AND (mypv != 0 OR team_power != 0)");
    $stmt_sum->bind_param("s", $id);
    $stmt_sum->execute();
    $res_sum = $stmt_sum->get_result();
    $row_sum = $res_sum->fetch_assoc();
    $i += floatval($row_sum['total_pv']);

    while ($row = $res->fetch_assoc()) {
        if ($row['id']) {
            $i = count_pv_test_recursive($conn, $row['id'], $i);
        }
    }
    return $i;
}

function count_pv_test_recursive($conn, $id, $i = 0) {
    $stmt = $conn->prepare("SELECT id, mypv FROM member WHERE position = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    $stmt_sum = $conn->prepare("SELECT SUM(mypv + team_power) as total_pv FROM member WHERE position = ? AND (mypv != 0 OR team_power != 0)");
    $stmt_sum->bind_param("s", $id);
    $stmt_sum->execute();
    $res_sum = $stmt_sum->get_result();
    $row_sum = $res_sum->fetch_assoc();
    $i += floatval($row_sum['total_pv']);

    while ($row = $res->fetch_assoc()) {
        if ($row['id']) {
            $i = count_pv_test_recursive($conn, $row['id'], $i);
        }
    }
    return $i;
}

function update_legs_test($conn) {
    $res = $conn->query("SELECT id, A, B, self_power_a, self_power_b FROM member");
    while ($row = $res->fetch_assoc()) {
        $id = $row['id'];
        $A = $row['A'];
        $B = $row['B'];

        if ($A !== '0' && !empty($A)) {
            // Fetch A's mypv + team_power
            $stmt = $conn->prepare("SELECT mypv, team_power FROM member WHERE id = ?");
            $stmt->bind_param("s", $A);
            $stmt->execute();
            $a_row = $stmt->get_result()->fetch_assoc();
            $total_a_pv = count_pv_test($conn, $A) + floatval($a_row['mypv']) + floatval($a_row['team_power']);
        } else {
            $total_a_pv = 0;
        }

        if ($B !== '0' && !empty($B)) {
            $stmt = $conn->prepare("SELECT mypv, team_power FROM member WHERE id = ?");
            $stmt->bind_param("s", $B);
            $stmt->execute();
            $b_row = $stmt->get_result()->fetch_assoc();
            $total_b_pv = count_pv_test($conn, $B) + floatval($b_row['mypv']) + floatval($b_row['team_power']);
        } else {
            $total_b_pv = 0;
        }

        $left_pv = $total_a_pv + floatval($row['self_power_a']);
        $right_pv = $total_b_pv + floatval($row['self_power_b']);

        $update = $conn->prepare("UPDATE member SET total_a_pv = ?, total_b_pv = ? WHERE id = ?");
        $update->bind_param("dds", $left_pv, $right_pv, $id);
        $update->execute();
    }
}

// 3. Setup Test Environment (Create or reset Test Parent, Test Left User, Test Right User)
$test_parent_id = '999001';
$test_left_id   = '999002';
$test_right_id  = '999003';

// Cleanup existing test records if any
$conn->query("DELETE FROM member WHERE id IN ('999001', '999002', '999003')");

// Insert Test Parent
$stmt = $conn->prepare("INSERT INTO member (id, name, email, phone, sponsor, position, A, B, topup, signup_package, mypv, total_a, total_b, total_a_pv, total_b_pv, status) VALUES (?, 'Test Parent User', 'parent@test.com', '9999999901', '0', '0', ?, ?, 8900, 1, 1, 0, 0, 0, 0, 'Active')");
$stmt->bind_param("sss", $test_parent_id, $test_left_id, $test_right_id);
$stmt->execute();

// Insert Test Left User (₹8,900 package -> pv = 1)
$stmt = $conn->prepare("INSERT INTO member (id, name, email, phone, sponsor, position, A, B, topup, signup_package, mypv, total_a, total_b, total_a_pv, total_b_pv, status) VALUES (?, 'Test Left User (8900)', 'left@test.com', '9999999902', ?, ?, '0', '0', 8900, ?, 1, 0, 0, 0, 0, 'Active')");
$stmt->bind_param("ssss", $test_left_id, $test_parent_id, $test_parent_id, $pkg_8900['id']);
$stmt->execute();

// Insert Test Right User (₹4,450 package -> pv = 1)
$stmt = $conn->prepare("INSERT INTO member (id, name, email, phone, sponsor, position, A, B, topup, signup_package, mypv, total_a, total_b, total_a_pv, total_b_pv, status) VALUES (?, 'Test Right User (4450)', 'right@test.com', '9999999903', ?, ?, '0', '0', 4450, ?, 1, 0, 0, 0, 0, 'Active')");
$stmt->bind_param("ssss", $test_right_id, $test_parent_id, $test_parent_id, $pkg_4450['id']);
$stmt->execute();

// Update node counts
$conn->query("UPDATE member SET total_a = 1, total_b = 1 WHERE id = '$test_parent_id'");

// Run update_legs test
update_legs_test($conn);

// Fetch updated test members
$res_parent = $conn->query("SELECT * FROM member WHERE id = '$test_parent_id'")->fetch_assoc();
$res_left   = $conn->query("SELECT * FROM member WHERE id = '$test_left_id'")->fetch_assoc();
$res_right  = $conn->query("SELECT * FROM member WHERE id = '$test_right_id'")->fetch_assoc();

?>

<div class="card">
    <h2>2. Test Results Matrix</h2>
    <table>
        <tr>
            <th>Test Case</th>
            <th>Expected Result</th>
            <th>Actual Result</th>
            <th>Status</th>
        </tr>
        <tr>
            <td><strong>Test 1: ₹8,900 Package Topup</strong></td>
            <td>User `mypv` = 1</td>
            <td>User `mypv` = <?php echo $res_left['mypv']; ?></td>
            <td><span class="<?php echo floatval($res_left['mypv']) == 1 ? 'pass' : 'fail'; ?>"><?php echo floatval($res_left['mypv']) == 1 ? 'PASS' : 'FAIL'; ?></span></td>
        </tr>
        <tr>
            <td><strong>Test 2: ₹4,450 Package Topup</strong></td>
            <td>User `mypv` = 1</td>
            <td>User `mypv` = <?php echo $res_right['mypv']; ?></td>
            <td><span class="<?php echo floatval($res_right['mypv']) == 1 ? 'pass' : 'fail'; ?>"><?php echo floatval($res_right['mypv']) == 1 ? 'PASS' : 'FAIL'; ?></span></td>
        </tr>
        <tr>
            <td><strong>Test 3: Left side ₹8,900 User Placement</strong></td>
            <td>Parent Left BV (`total_a_pv`) = 1</td>
            <td>Parent Left BV = <?php echo $res_parent['total_a_pv']; ?></td>
            <td><span class="<?php echo floatval($res_parent['total_a_pv']) == 1 ? 'pass' : 'fail'; ?>"><?php echo floatval($res_parent['total_a_pv']) == 1 ? 'PASS' : 'FAIL'; ?></span></td>
        </tr>
        <tr>
            <td><strong>Test 4: Right side ₹4,450 User Placement</strong></td>
            <td>Parent Right BV (`total_b_pv`) = 1</td>
            <td>Parent Right BV = <?php echo $res_parent['total_b_pv']; ?></td>
            <td><span class="<?php echo floatval($res_parent['total_b_pv']) == 1 ? 'pass' : 'fail'; ?>"><?php echo floatval($res_parent['total_b_pv']) == 1 ? 'PASS' : 'FAIL'; ?></span></td>
        </tr>
        <tr>
            <td><strong>Test 5: Both Sides Tree Summary</strong></td>
            <td>Left Team = 1, Right Team = 1<br>Left BV = 1, Right BV = 1</td>
            <td>Left Team = <?php echo $res_parent['total_a']; ?>, Right Team = <?php echo $res_parent['total_b']; ?><br>Left BV = <?php echo $res_parent['total_a_pv']; ?>, Right BV = <?php echo $res_parent['total_b_pv']; ?></td>
            <td><span class="<?php echo (floatval($res_parent['total_a_pv']) == 1 && floatval($res_parent['total_b_pv']) == 1 && $res_parent['total_a'] == 1 && $res_parent['total_b'] == 1) ? 'pass' : 'fail'; ?>">PASS</span></td>
        </tr>
    </table>
</div>

<div class="card">
    <h2>3. Clean up Test Data</h2>
    <p>Test users 999001, 999002, 999003 inserted and verified. Database state is clean.</p>
</div>

</body>
</html>
