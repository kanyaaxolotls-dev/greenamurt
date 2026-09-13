<?php
require_once __DIR__ . '/../system/application/config/database.php';
$db_cfg = $db['default'];
$conn = new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);

if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

echo "=== EARNINGS BY TYPE ===\n";
$res = $conn->query("SELECT type, COUNT(*) as count, SUM(amount) as total FROM earning GROUP BY type");
while($r = $res->fetch_assoc()) {
    echo "Type: " . str_pad($r["type"], 25) . " | Count: " . str_pad($r["count"], 5) . " | Total: ₹" . number_format($r["total"], 2) . "\n";
}

echo "\n=== TOTAL EARNINGS OVERALL ===\n";
$tot_res = $conn->query("SELECT SUM(amount) as grand_total FROM earning");
$gt = $tot_res->fetch_assoc();
echo "Grand Total: ₹" . number_format($gt["grand_total"] ?? 0, 2) . "\n\n";

echo "=== MEMBER-WISE EARNINGS & WALLET ===\n";
$q = "SELECT m.id, m.name, m.status, m.topup, 
        COALESCE(SUM(CASE WHEN e.type = 'Direct Sponsor Income' THEN e.amount ELSE 0 END), 0) as direct_inc,
        COALESCE(SUM(CASE WHEN e.type = 'Matching Income' THEN e.amount ELSE 0 END), 0) as match_inc,
        COALESCE(SUM(CASE WHEN e.type = 'Direct Referral Bonus' AND e.levlno = 1 THEN e.amount ELSE 0 END), 0) as drb_l1,
        COALESCE(SUM(CASE WHEN e.type = 'Direct Referral Bonus' AND e.levlno = 2 THEN e.amount ELSE 0 END), 0) as drb_l2,
        COALESCE(SUM(e.amount), 0) as total_earned,
        COALESCE(w.balance, 0) as wallet_bal
      FROM member m
      LEFT JOIN earning e ON m.id = e.userid
      LEFT JOIN wallet w ON m.id = w.userid
      WHERE m.id != '1000'
      GROUP BY m.id
      HAVING total_earned > 0 OR wallet_bal > 0
      ORDER BY total_earned DESC, m.id ASC";

$m_res = $conn->query($q);
printf("%-8s | %-28s | %-10s | %-10s | %-10s | %-10s | %-12s | %-10s\n", "User ID", "Name", "Direct", "Matching", "DRB L1", "DRB L2", "Total Earned", "Wallet Bal");
echo str_repeat("-", 110) . "\n";
while($m = $m_res->fetch_assoc()) {
    printf("%-8s | %-28s | ₹%-9.2f | ₹%-9.2f | ₹%-9.2f | ₹%-9.2f | ₹%-11.2f | ₹%-9.2f\n", 
        $m["id"], 
        substr($m["name"], 0, 28), 
        $m["direct_inc"], 
        $m["match_inc"], 
        $m["drb_l1"], 
        $m["drb_l2"], 
        $m["total_earned"], 
        $m["wallet_bal"]
    );
}

echo "\n=== LAPSED EARNINGS ===\n";
$laps_res = $conn->query("SELECT user_id, income_type, SUM(amount) as total_lapsed, reason FROM laps_earning GROUP BY user_id, income_type, reason");
if ($laps_res && $laps_res->num_rows > 0) {
    while($l = $laps_res->fetch_assoc()) {
        echo "User #{$l["user_id"]} | {$l["income_type"]} | ₹" . number_format($l["total_lapsed"], 2) . " | Reason: {$l["reason"]}\n";
    }
} else {
    echo "No lapsed earnings.\n";
}
