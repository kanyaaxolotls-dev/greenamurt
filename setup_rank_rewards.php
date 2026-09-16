<?php
/**
 * Setup Script: Insert 12 Rank & Reward Settings
 * GreenAmrutAyurveda
 */

if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__ . '/');
}
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

$db_file = __DIR__ . '/system/application/config/database.php';
if (!file_exists($db_file)) {
    $db_file = __DIR__ . '/application/config/database.php';
}
if (!file_exists($db_file)) {
    $db_file = __DIR__ . '/../system/application/config/database.php';
}
include($db_file);

$db_config = $db['default'];
$host   = !empty($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
$user   = $db_config['username'];
$pass   = $db_config['password'];
$dbname = $db_config['database'];

$mysqli = @new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("<h2 style='color:red;'>Database connection failed: " . htmlspecialchars($mysqli->connect_error) . "</h2>");
}

// 1. Check reward_setting columns and add missing columns if needed
$res_cols = $mysqli->query("SHOW COLUMNS FROM reward_setting");
$cols = [];
if ($res_cols) {
    while ($c = $res_cols->fetch_assoc()) {
        $cols[] = $c['Field'];
    }
}

if (!in_array('reward_gift', $cols)) {
    $mysqli->query("ALTER TABLE reward_setting ADD COLUMN reward_gift VARCHAR(255) NULL AFTER reward_name");
}
if (!in_array('total_member', $cols)) {
    $mysqli->query("ALTER TABLE reward_setting ADD COLUMN total_member INT(11) NOT NULL DEFAULT 0 AFTER reward_duration");
}
if (!in_array('reward_amt', $cols)) {
    $mysqli->query("ALTER TABLE reward_setting ADD COLUMN reward_amt DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER total_member");
}
if (!in_array('grace_amt', $cols)) {
    $mysqli->query("ALTER TABLE reward_setting ADD COLUMN grace_amt DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER reward_amt");
}
if (!in_array('level_no', $cols)) {
    $mysqli->query("ALTER TABLE reward_setting ADD COLUMN level_no INT(11) NOT NULL DEFAULT 0 AFTER id");
}

// 2. Define 12 Ranks & Rewards
$rewards = [
    [
        'level_no'        => 1,
        'reward_name'     => 'Pioneer',
        'reward_gift'     => 'Smart Watch / Mixer Grinder',
        'pairs'           => 12,
        'reward_duration' => 30,
        'reward_amt'      => 6000.00,
        'grace_amt'       => 3000.00
    ],
    [
        'level_no'        => 2,
        'reward_name'     => 'Rising Star',
        'reward_gift'     => 'Android Smartphone / Tablet',
        'pairs'           => 30,
        'reward_duration' => 45,
        'reward_amt'      => 15000.00,
        'grace_amt'       => 7500.00
    ],
    [
        'level_no'        => 3,
        'reward_name'     => 'Achiever',
        'reward_gift'     => '43" Smart TV / Laptop',
        'pairs'           => 75,
        'reward_duration' => 60,
        'reward_amt'      => 35000.00,
        'grace_amt'       => 17500.00
    ],
    [
        'level_no'        => 4,
        'reward_name'     => 'Leader',
        'reward_gift'     => 'Electric Scooter / Bike Fund',
        'pairs'           => 150,
        'reward_duration' => 90,
        'reward_amt'      => 70000.00,
        'grace_amt'       => 35000.00
    ],
    [
        'level_no'        => 5,
        'reward_name'     => 'Master',
        'reward_gift'     => 'Royal Enfield / iPhone + Laptop',
        'pairs'           => 350,
        'reward_duration' => 120,
        'reward_amt'      => 150000.00,
        'grace_amt'       => 75000.00
    ],
    [
        'level_no'        => 6,
        'reward_name'     => 'Icon',
        'reward_gift'     => 'Alto / Kwid (Down Payment + Gold)',
        'pairs'           => 750,
        'reward_duration' => 180,
        'reward_amt'      => 350000.00,
        'grace_amt'       => 175000.00
    ],
    [
        'level_no'        => 7,
        'reward_name'     => 'Director',
        'reward_gift'     => 'Swift / Baleno (Full Car Fund)',
        'pairs'           => 1500,
        'reward_duration' => 240,
        'reward_amt'      => 700000.00,
        'grace_amt'       => 350000.00
    ],
    [
        'level_no'        => 8,
        'reward_name'     => 'Vice President',
        'reward_gift'     => 'Mahindra Thar / Creta / SUV Fund',
        'pairs'           => 350,
        'reward_duration' => 360,
        'reward_amt'      => 1500000.00,
        'grace_amt'       => 750000.00
    ],
    [
        'level_no'        => 9,
        'reward_name'     => 'President',
        'reward_gift'     => 'Fortuner / Luxury Car Fund',
        'pairs'           => 7500,
        'reward_duration' => 540,
        'reward_amt'      => 3500000.00,
        'grace_amt'       => 1750000.00
    ],
    [
        'level_no'        => 10,
        'reward_name'     => 'Crown President',
        'reward_gift'     => 'Luxury Dream House / Villa Fund',
        'pairs'           => 15000,
        'reward_duration' => 720,
        'reward_amt'      => 7500000.00,
        'grace_amt'       => 3750000.00
    ],
    [
        'level_no'        => 11,
        'reward_name'     => 'Global Legend',
        'reward_gift'     => 'Bungalow + Luxury Car Fund',
        'pairs'           => 35000,
        'reward_duration' => 0,
        'reward_amt'      => 17500000.00,
        'grace_amt'       => 17500000.00
    ],
    [
        'level_no'        => 12,
        'reward_name'     => 'Universal Champion',
        'reward_gift'     => 'Universal Champion Fund / Property',
        'pairs'           => 75000,
        'reward_duration' => 0,
        'reward_amt'      => 37500000.00,
        'grace_amt'       => 37500000.00
    ]
];

// Vice president pairs check
$rewards[7]['pairs'] = 3500;

// Truncate and cleanly insert all 12 ranks
$mysqli->query("TRUNCATE TABLE reward_setting");

foreach ($rewards as $r) {
    $lvl      = $r['level_no'];
    $name     = $mysqli->real_escape_string($r['reward_name']);
    $gift     = $mysqli->real_escape_string($r['reward_gift']);
    $pairs    = $r['pairs'];
    $duration = $r['reward_duration'];
    $amt      = $r['reward_amt'];
    $grace    = $r['grace_amt'];

    $sql = "INSERT INTO reward_setting (level_no, reward_name, reward_gift, total_member, A, B, reward_duration, reward_amt, grace_amt) 
            VALUES ('$lvl', '$name', '$gift', '$pairs', '$pairs', '$pairs', '$duration', '$amt', '$grace')";
    $mysqli->query($sql);
}

// Also check and sync rank_system table if present
$res_rank = $mysqli->query("SHOW TABLES LIKE 'rank_system'");
if ($res_rank && $res_rank->num_rows > 0) {
    $mysqli->query("TRUNCATE TABLE rank_system");
    foreach ($rewards as $r) {
        $name  = $mysqli->real_escape_string($r['reward_name']);
        $pairs = $r['pairs'];
        $amt   = $r['reward_amt'];
        $mysqli->query("INSERT INTO rank_system (rank_name, total_member, amount) VALUES ('$name', '$pairs', '$amt')");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rank & Reward Setup Completed</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f4f8; padding: 25px; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h1 { color: #1e7e34; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background: #28a745; color: white; }
        tr:nth-child(even) { background: #f9fbf9; }
        .badge { background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .btn { display: inline-block; background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h1>✅ All 12 Rank & Reward Settings Successfully Updated!</h1>
    <p style="color: #555; font-size: 16px;">Green Amrut Ayurveda - Rank & Reward Master Chart is active and synchronized in the database.</p>
    <table>
        <thead>
            <tr>
                <th>Level</th>
                <th>Rank Name</th>
                <th>Alternative Gift Option</th>
                <th>Pairs Required</th>
                <th>Duration (Days)</th>
                <th>100% Cash Reward</th>
                <th>50% Grace Reward</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rewards as $r): ?>
            <tr>
                <td><strong>#<?= $r['level_no'] ?></strong></td>
                <td><span class="badge"><?= htmlspecialchars($r['reward_name']) ?></span></td>
                <td><?= htmlspecialchars($r['reward_gift']) ?></td>
                <td><strong><?= number_format($r['pairs']) ?></strong></td>
                <td><?= $r['reward_duration'] > 0 ? $r['reward_duration'] . ' Days' : '<span style="color:#28a745;">No Time Limit</span>' ?></td>
                <td style="color:#1b5e20; font-weight:bold;">₹<?= number_format($r['reward_amt']) ?></td>
                <td style="color:#b71c1c; font-weight:bold;">₹<?= number_format($r['grace_amt']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="../admin/setting/reward_setting" class="btn">Go to Admin Reward Settings</a>
</div>
</body>
</html>
