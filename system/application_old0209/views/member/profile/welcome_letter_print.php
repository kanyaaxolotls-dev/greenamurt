<div class="col">
    <div class="card bg-light shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="mb-0"><?php echo $title ?></h3>
                </div>
            </div>
        </div>
        <div class="card-body" style="overflow: scroll">
            <?php
                $detail        = $this->db_model->select_multi('*', 'member', array('id' => $this->session->user_id));
                $detail1       = $this->db_model->select_multi('prod_name, prod_price', 'product', array('id' => $detail->signup_package));
                $dob           = $this->db_model->select_multi('*', 'member_profile', array('userid' => $detail->id));
                $sponsor_name  = $this->db_model->select_multi('name', 'member', array('id' => $detail->sponsor));
                $formattedDate = date('d-m-Y', strtotime($detail->join_time));
            ?>
            <table class="table table-borderless mb-4" style="height: 140px;">
                <tbody>
                    <tr>
                        <td>
                            <p><?= config_item('company_name') ?></p>
                            <p><?php echo config_item('company_address') ?></p>
                        </td>
                        <td><img src="<?= base_url() ?>uploads/logo.png" alt="<?= config_item('company_name') ?> Logo" class="float-right" style="height: 100px; width: 160px;" /></td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <table class="table table-borderless" style="height: 140px;">
                <tbody>
                    <tr>
                        <td>
                            <p><strong>Dear <?= $detail->name ?>,</strong></p>
                            <p><strong>Register Date:</strong> <?= $formattedDate ?></p>
                            <p class="text-center"><strong><u>ACCEPTANCE LETTER</u></strong></p>
                            <p class="text-center">Acceptance of Online agreement / contract with <?= config_item('company_name') ?></p>
                            <table class="table table-borderless" style="height: 180px; width: 100%;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>
                                                Associate Id: <?= $detail->id ?><br />
                                                Full Name: <?= $detail->name ?><br />
                                                Permanent Address: <?= $detail->address ?><br />
                                                Contact Number: <?= $detail->phone ?><br />
                                                Email: <?= $detail->email ?><br />
                                                Package Purchased: <?= $detail1->prod_name ?><br />
                                                Associate Sponsor: Mr/Ms. <?= $detail->sponsor ?> <?= $sponsor_name ?><br />
                                                Price: INR <?= $detail->topup ?> Only<br />
                                                Date of Birth: <?= $dob->date_of_birth ?>
                                            </p>
                                        </td>
                                        <td class="text-right">
                                            <table class="table table-bordered" style="width: 108px;">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">Photograph</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr />
                            <p class="text-center"><strong>Declaration</strong></p>
                            <p>I solemnly declare and affirm as under:</p>
                            <ol>
                                <li>I declare that I am of the age of 18 years or more and competent to contract.</li>
                                <li>I have read and understood the terms and conditions for appointment of <?= config_item('company_name') ?> Retailer/associate (Direct seller) of the company, and accept them.</li>
                                <li>I have also gone through the company's official website including FAQ's, Printed materials, and brochures. I agree to the contents of the materials and am convinced about the business and I have applied to appoint me as a Direct seller on my own volition.</li>
                                <li>I declare that I have not been given any assurance or promise or inducement by the company or its directors in regards to any fixed income incentive, price, or benefit on account of the products purchased by me.</li>
                                <li>I have clearly understood that eligibility of income exclusively depends on my performance as per the business plan of the company. I further agree that the company reserves the rights to change the Business Plan at any point of time without any prior notice.</li>
                                <li>I undertake not to misguide or induce dishonestly anybody to join the Company.</li>
                                <li>I hereby agree to submit all disputes to dispute redressal cell / Arbitration as provided in the terms and conditions of the company.</li>
                                <li>I hereby declare that I am signing this "DECLARATION" with complete understanding and with my FREE WILL, without any PRESSURE / UNDUE INFLUENCE or INDUCEMENT.</li>
                                <li>I hereby agree and adhere to the terms and conditions as given on the website of the company (<a target="_" href="http://<?= $_SERVER['HTTP_HOST'] ?>"><?= $_SERVER['HTTP_HOST'] ?></a>) and as mentioned above to purchase the product and to do the Direct Seller activities.</li>
                            </ol>
                            <table class="table table-bordered mt-4">
                                <tbody>
                                    <tr>
                                        <td class="text-center"><strong>Signature of Joining Direct Seller</strong><br />(<?= config_item('company_name') ?> Purchaser)</td>
                                        <td class="text-center"><strong>Signature of Sponsoring</strong><br /><?= config_item('company_name') ?> Associates</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p>Please submit this form along with the following documents to the company office:</p>
                            <ol>
                                <li>A photocopy of any one self-attested Govt. issued photo identification document.</li>
                                <li>A photocopy of any one self-attested Govt. issued address proof.</li>
                                <li>Self-attested photocopy of PAN card.</li>
                            </ol>
                            <hr />
                            <p class="small">
                                NOTE: It is the sole responsibility of the joining <?= config_item('company_name') ?> Direct Seller to submit authentic documents and information, any discrepancy or any civil/criminal liability arising due to this regard would be the full and complete responsibility of the said joining <?= config_item('company_name') ?> Direct Seller. The company shall not be responsible in this regard in any way.
                            </p>
                        </td>
                        <td class="text-right"></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="<?php echo site_url('member/welcome_letter_details' . $e->id); ?>" target="_blank" class="btn btn-info btn-sm">Print</a>
            </div>
        </div>
    </div>
</div>
