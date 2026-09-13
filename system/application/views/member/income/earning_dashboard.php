<?php
    $calc_total_inc = floatval($this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id)));

    $calc_sponsor_b = floatval($this->db->select_sum('amount')->from('earning')->where('userid', $this->session->user_id)->where_in('type', array('Direct Sponsor Income', 'Direct Sponsor Commission', 'Direct Income'))->get()->row()->amount ?? 0);

    $calc_team_b = floatval($this->db->select_sum('amount')->from('earning')->where('userid', $this->session->user_id)->where_in('type', array('Matching Income', 'Sales Matching Income', 'Team Bonus'))->get()->row()->amount ?? 0);

    $calc_drb_l1 = floatval($this->db->select_sum('amount')->from('earning')->where('userid', $this->session->user_id)->where_in('type', array('Direct Referral Bonus', 'Direct Referral Bonus Level 1'))->where('levlno', 1)->get()->row()->amount ?? 0);

    $calc_drb_l2 = floatval($this->db->select_sum('amount')->from('earning')->where('userid', $this->session->user_id)->where_in('type', array('Direct Referral Bonus (Level 2)', 'Direct Referral Bonus Level 2'))->where('levlno', 2)->get()->row()->amount ?? 0);

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
            ['title' => 'Direct Referral Bonus Level 1', 'value' => $calc_drb_l1],
            ['title' => 'Direct Referral Bonus Level 2', 'value' => $calc_drb_l2],
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
                                    <span class="counter-value" data-target="<?php echo $card['value']; ?>"><?php echo (is_numeric($card['value']) && floor($card['value']) == $card['value']) ? intval($card['value']) : $card['value']; ?></span>
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

