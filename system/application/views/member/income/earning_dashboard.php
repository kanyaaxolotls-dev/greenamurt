<?php
    $calc_total_inc = isset($total_income) ? $total_income : ($this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id)) + 0);

    $calc_sponsor_b = isset($direct_sponsor_income) ? $direct_sponsor_income : (
        $this->db->select_sum('amount')->from('earning')->where('userid', $this->session->user_id)->group_start()->where_in('type', array('Direct Sponsor Income', 'Direct Sponsor Commission', 'Active Bonus', 'Referral Reward', 'Direct Income', 'Referral Income'))->group_end()->get()->row()->amount + 0
    );

    $calc_team_b = isset($matching) ? $matching : (
        $this->db->select_sum('amount')->from('earning')->where('userid', $this->session->user_id)->group_start()->where_in('type', array('Team Bonus', 'Matching Income', 'Matching'))->group_end()->get()->row()->amount + 0
    );

    $calc_matching_sp = isset($sp_level_income2) ? $sp_level_income2 : (
        $this->db->select_sum('amount')->from('earning')->where('userid', $this->session->user_id)->group_start()->where_in('type', array('Active Sponsor Bonus', 'Matching Sponsor Inc', 'Sponsor Income', 'Sponsor Level Inc'))->group_end()->get()->row()->amount + 0
    );

    $calc_today_sp = isset($today_sponsor_income) ? $today_sponsor_income : (
        $this->db->select_sum('amount')->from('earning')->where('userid', $this->session->user_id)->where('date', date('Y-m-d'))->group_start()->where_in('type', array('Direct Sponsor Income', 'Direct Sponsor Commission'))->group_end()->get()->row()->amount + 0
    );

    $sections = [
        'Total Team And Pairs' => [
            ['title' => 'Left PV', 'value' => $detail->total_a_pv],
            ['title' => 'Right PV', 'value' => $detail->total_b_pv],
            ['title' => 'Left Team', 'value' => $detail->total_a],
            ['title' => 'Right Team', 'value' => $detail->total_b]
        ],
        'My Incomes' => [
            ['title' => 'Total Income', 'value' => $calc_total_inc],
            ['title' => 'Direct Sponsor Income', 'value' => $calc_sponsor_b],
            ['title' => 'Sales Matching Income', 'value' => $calc_team_b],
            ['title' => 'Matching Sponsor Income', 'value' => $calc_matching_sp],
            ['title' => 'Today Sponsor Income', 'value' => $calc_today_sp],
        ],
        'Balance Pairs' => [
            ['title' => 'Balance Left PV', 'value' => ($detail->total_a_pv - $detail->paid_a_pv)],
            ['title' => 'Balance Right PV', 'value' => ($detail->total_b_pv - $detail->paid_b_pv)],
            ['title' => 'Total Match PV', 'value' => $detail->total_pairs]
        ],
        'Payout' => [
            ['title' => 'Payment Received', 'value' => $p_Paid],
            ['title' => 'Pending', 'value' => $u_Paid],
            ['title' => 'Hold', 'value' => $h_Paid]
        ]
    ];
?>

<?php foreach ($sections as $sectionTitle => $cards): ?>
    <div class="row">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $sectionTitle; ?></h4>
        </div>
    </div>
    <div class="row">
        <?php
            $count = count($cards);
            $colClass = 'col-md-4';  

            if ($count === 5) {
                $colClass = 'col-md-4 col-xl';
            } elseif ($count === 4) {
                $colClass = 'col-md-3';
            } elseif ($count === 6) {
                $colClass = 'col-md-2';
            } elseif ($count === 2) {
                $colClass = 'col-md-6';
            } elseif ($count === 1) {
                $colClass = 'col-md-12';
            }
        ?>

        <?php foreach ($cards as $index => $card): ?>
            <div class="<?php echo $colClass; ?> col-12 mb-3">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-dark mb-3 lh-1 d-block text-truncate"><?php echo $card['title']; ?></span>
                                <h4 class="mb-3">
                                    <?php if (strpos($sectionTitle, 'Payout') !== false || strpos($sectionTitle, 'Incomes') !== false || strpos($card['title'], 'Income') !== false || strpos($card['title'], 'Bonus') !== false): ?>
                                        <i class="fas fa-rupee-sign"></i>
                                    <?php endif; ?>
                                    <span class="counter-value" data-target="<?php echo $card['value']; ?>"></span>
                                </h4>
                            </div>
                            <div class="flex-shrink-0 text-end dash-widget">
                                <div id="mini-chart<?php echo ($index % 3) + 1; ?>" data-colors='["#1c84ee", "#33c38e"]' class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

