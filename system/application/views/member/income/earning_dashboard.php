<?php
    $sections = [
        'My Incomes' => [
            ['title' => 'Today Matching Income', 'value' => $today_matching],
            ['title' => 'Total Matching Income', 'value' => $matching],
            ['title' => 'Today Sponsor Income', 'value' => $this->db_model->sum('amount', 'earning', ['type' => 'Direct Sponsor Commission', 'userid' => $this->session->user_id,'date' => date('Y-m-d')])],
            ['title' => 'Total Sponsor Income', 'value' => $this->db_model->sum('amount', 'earning', ['type' => 'Direct Sponsor Commission', 'userid' => $this->session->user_id])],
        ],
        /* Re-purchase & Royalty incomes section commented out
        'Re-purchase' => [
            ['title' => 'Monthly Repurchase Income', 'value' => $monthly_repurchase_inc],
            ['title' => 'Total Repurchase Income', 'value' => $total_repurchase_inc],
            ['title' => 'Monthly Royalty Income', 'value' => $monthly_royalty_inc],
            ['title' => 'Total Royalty Income', 'value' => $total_royalty_inc],
        ],
        */
        'Balance Pairs' => [
            ['title' => 'Balance Left PV', 'value' => ($detail->total_a_pv - $detail->paid_a_pv)],
            ['title' => 'Balance Right PV', 'value' => ($detail->total_b_pv - $detail->paid_b_pv)],
            ['title' => 'Total Match PV', 'value' => $detail->total_pairs]
        ],
        'Total Team And Pairs' => [
            ['title' => 'Left PV', 'value' => $detail->total_a_pv],
            ['title' => 'Right PV', 'value' => $detail->total_b_pv],
            ['title' => 'Left Team', 'value' => $detail->total_a],
            ['title' => 'Right Team', 'value' => $detail->total_b]
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

            if ($count === 4) {
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
                                    <?php if (strpos($sectionTitle, 'Payout') !== false || strpos($card['title'], 'Income') !== false): ?>
                                        <i class="fas fa-rupee-sign"></i>
                                    <?php endif; ?>
                                    <span class="counter-value" data-target="<?php echo $card['value']; ?>"></span>
                                </h4>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
