<?php
/**
 * READ-ONLY Detailed Trace Script for Users 311461, 711364, 243026 & UI-to-Controller Flow
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

// 1. Fetch Target Users
$target_user_ids = ['311461', '711364', '243026'];
$members = [];
$res_m = $conn->query("SELECT * FROM member WHERE id IN ('311461', '711364', '243026')");
if ($res_m) {
    while ($row = $res_m->fetch_assoc()) {
        $members[$row['id']] = $row;
    }
}

// 2. Fetch All Products
$products = [];
$res_p = $conn->query("SELECT * FROM product");
if ($res_p) {
    while ($row = $res_p->fetch_assoc()) {
        $products[$row['id']] = $row;
    }
}

// 3. Fetch product_sale for target users
$sales = [];
$res_s = $conn->query("SELECT * FROM product_sale WHERE userid IN ('311461', '711364', '243026')");
if ($res_s) {
    while ($row = $res_s->fetch_assoc()) {
        $sales[] = $row;
    }
}

// 4. Fetch ALL product_sale with product_id = 0
$sales_p0 = [];
$res_p0 = $conn->query("SELECT * FROM product_sale WHERE product_id = '0' OR product_id = 0 ORDER BY id DESC LIMIT 20");
if ($res_p0) {
    while ($row = $res_p0->fetch_assoc()) {
        $sales_p0[] = $row;
    }
}

// 5. Fetch Epins used by target users
$epins = [];
$res_ep = $conn->query("SELECT * FROM epin WHERE used_by IN ('311461', '711364', '243026') OR issue_to IN ('311461', '711364', '243026')");
if ($res_ep) {
    while ($row = $res_ep->fetch_assoc()) {
        $epins[] = $row;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Greenamrut Ayurveda - Comprehensive Topup & BV Trace</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; color: #333; margin: 20px; }
        h1, h2, h3 { color: #2c3e50; }
        .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px 10px; text-align: left; }
        th { background: #edf2f7; color: #2d3748; }
        tr:nth-child(even) { background: #f8fafc; }
        .highlight { background: #fff5f5; color: #c53030; font-weight: bold; }
        pre { background: #2d3748; color: #fff; padding: 15px; border-radius: 6px; overflow-x: auto; font-family: monospace; font-size: 13px; }
    </style>
</head>
<body>

<h1>Greenamrut Ayurveda - Comprehensive Topup & BV Flow Trace</h1>

<div class="card">
    <h2>1. Target User Account State (`member` Table)</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>`signup_package`</th>
                <th>`topup`</th>
                <th>`mypv`</th>
                <th>`position`</th>
                <th>`placement_leg`</th>
                <th>Left Child `A`</th>
                <th>Right Child `B`</th>
                <th>`total_a_pv` (Left BV)</th>
                <th>`total_b_pv` (Right BV)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (['243026', '311461', '711364'] as $uid): ?>
                <?php if (isset($members[$uid])): $u = $members[$uid]; ?>
                <tr>
                    <td><strong><?php echo $u['id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($u['name']); ?></td>
                    <td><?php echo htmlspecialchars($u['signup_package']); ?></td>
                    <td>₹<?php echo htmlspecialchars($u['topup']); ?></td>
                    <td class="<?php echo floatval($u['mypv']) > 0 ? '' : 'highlight'; ?>"><?php echo var_export($u['mypv'], true); ?></td>
                    <td><?php echo htmlspecialchars($u['position']); ?></td>
                    <td><?php echo htmlspecialchars($u['placement_leg']); ?></td>
                    <td><?php echo htmlspecialchars($u['A']); ?></td>
                    <td><?php echo htmlspecialchars($u['B']); ?></td>
                    <td><?php echo htmlspecialchars($u['total_a_pv']); ?></td>
                    <td><?php echo htmlspecialchars($u['total_b_pv']); ?></td>
                    <td><?php echo htmlspecialchars($u['status']); ?></td>
                </tr>
                <?php else: ?>
                <tr><td colspan="12">User ID <?php echo $uid; ?> not found in database.</td></tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h2>2. Products / Packages Table (`product`)</h2>
    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name (`prod_name`)</th>
                <th>Price (`prod_price`)</th>
                <th>Dealer Price (`dealer_price`)</th>
                <th>Configured PV (`pv`)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><strong><?php echo $p['id']; ?></strong></td>
                <td><?php echo htmlspecialchars($p['prod_name']); ?></td>
                <td>₹<?php echo htmlspecialchars($p['prod_price']); ?></td>
                <td>₹<?php echo htmlspecialchars($p['dealer_price']); ?></td>
                <td><strong><?php echo htmlspecialchars($p['pv']); ?></strong></td>
                <td><?php echo htmlspecialchars($p['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h2>3. Product Sales Records for Target Users (`product_sale` Table)</h2>
    <table>
        <thead>
            <tr>
                <th>Sale ID (`id`)</th>
                <th>User ID (`userid`)</th>
                <th>Product ID (`product_id`)</th>
                <th>Cost (`cost`)</th>
                <th>Order ID (`orderid`)</th>
                <th>Order By (`order_by`)</th>
                <th>Date (`date`)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sales)): ?>
                <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?php echo $s['id']; ?></td>
                    <td><?php echo $s['userid']; ?></td>
                    <td class="<?php echo ($s['product_id'] == 0 || $s['product_id'] == '0') ? 'highlight' : ''; ?>"><?php echo $s['product_id']; ?></td>
                    <td>₹<?php echo $s['cost']; ?></td>
                    <td><?php echo htmlspecialchars(isset($s['orderid']) ? $s['orderid'] : 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars(isset($s['order_by']) ? $s['order_by'] : 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars(isset($s['date']) ? $s['date'] : 'N/A'); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No product_sale records found for users 311461, 711364, 243026.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h2>4. Investigation of `product_sale` Records with `product_id = 0`</h2>
    <table>
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Cost</th>
                <th>Order ID</th>
                <th>Order By</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sales_p0)): ?>
                <?php foreach ($sales_p0 as $s): ?>
                <tr>
                    <td><?php echo $s['id']; ?></td>
                    <td><?php echo $s['userid']; ?></td>
                    <td class="highlight"><?php echo $s['product_id']; ?></td>
                    <td>₹<?php echo $s['cost']; ?></td>
                    <td><?php echo htmlspecialchars(isset($s['orderid']) ? $s['orderid'] : 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars(isset($s['order_by']) ? $s['order_by'] : 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars(isset($s['date']) ? $s['date'] : 'N/A'); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No records with product_id = 0 found in product_sale.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h2>5. E-Pin Records for Target Users (`epin` Table)</h2>
    <table>
        <thead>
            <tr>
                <th>Epin ID</th>
                <th>Epin Code</th>
                <th>Amount</th>
                <th>Issue To</th>
                <th>Used By</th>
                <th>Status</th>
                <th>Used Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($epins)): ?>
                <?php foreach ($epins as $ep): ?>
                <tr>
                    <td><?php echo $ep['id']; ?></td>
                    <td><?php echo htmlspecialchars($ep['epin']); ?></td>
                    <td>₹<?php echo htmlspecialchars($ep['amount']); ?></td>
                    <td><?php echo htmlspecialchars($ep['issue_to']); ?></td>
                    <td><?php echo htmlspecialchars($ep['used_by']); ?></td>
                    <td><?php echo htmlspecialchars($ep['status']); ?></td>
                    <td><?php echo htmlspecialchars($ep['used_time']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No epin records found for target users.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// 6. UI Form Inspection & Controller Code Trace
function find_topup_views() {
    $view_dir = __DIR__ . '/system/application/views';
    $results = [];
    if (!file_exists($view_dir)) return $results;

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($view_dir));
    foreach ($iterator as $file) {
        if ($file->isDir() || $file->getExtension() !== 'php') continue;
        $content = file_get_contents($file->getPathname());
        if (stripos($content, 'topup') !== false || stripos($content, 'activation') !== false || stripos($content, 'signup_package') !== false) {
            preg_match_all('/<form[^>]+action=["\']([^"\']+)["\']/i', $content, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $act) {
                    $results[] = [
                        'file' => str_replace(__DIR__, '', $file->getPathname()),
                        'action' => $act
                    ];
                }
            }
        }
    }
    return $results;
}

$topup_forms = find_topup_views();
?>

<div class="card">
    <h2>6. UI Form Actions Found in `system/application/views`</h2>
    <table>
        <thead>
            <tr>
                <th>View File</th>
                <th>Form Action URL / Controller Route</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($topup_forms)): ?>
                <?php foreach ($topup_forms as $tf): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tf['file']); ?></td>
                    <td><code><?php echo htmlspecialchars($tf['action']); ?></code></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2">No topup forms found in views.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
