<?php
        defined('BASEPATH') OR exit('Can we play bubu together ?');
        $config['company_name'] = "GA Herbal Multitrade Private Limited";
        $config['select_temp'] = "Eccomerce Without Functionality";
        $config['web_phone'] = "7020464644";
        $config['web_status'] = "Live";
        $config['web_email'] = "greenamrutayurveda@gmail.com";
        $config['company_address'] = "Pune";
        $config['ID_EXT'] = ''; # ID Extension eg: DM1001
        $config['currency'] = "₹                                                                                                                            "; # Sitewide currency
        $config['iso_currency'] = "₹                                                                                                                            "; # ISO Code of currency
        $config['leg'] = "2"; # 1, 2, 3, 4, 5(eg: for binary plan leg is 2)
        $config['show_leg_choose'] = "Yes"; ## Whether to show placement ID box at registration
        $config['show_placement_id'] = "Yes"; ## Whether to show select position option or not
        $config['autopool_registration'] = "No";
        $config['show_join_product'] = "No";
        $config['enable_epin'] = "Yes";
        $config['enable_pg'] = "Yes"; # Payment Gateway
        $config['free_registration'] = "Yes";
        $config['sms_on_join'] = "Yes";
        $config['top_id'] = "1001";
        $config['prevent_join_product_entry'] = "Yes";
        $config['enable_gap_commission'] = "Yes";
        $config['sms_api'] = "https://api.msg91.com/api/sendhttp.php?route=4&sender=AXOTLS&message={{msg}}&country=91&mobiles={{phone}}&authkey=284460AHrpiChjod5d246f5a";
        ## Format: https://apiurl.com?no={{phone}}&msg={{msg}}&other_parameters.
        $config['disable_registration'] = "No";
        $config['fix_income'] = "No";
        $config['task_based'] = "No";
        $config['auto_payout'] = "";
        
        $config['account_number'] = "1234567890";
        $config['ifsc_Code'] = "ABCD123456";
        $config['bank_branch'] = "Pune";
        $config['account_name'] = "Green Amrut Herbal Multitrade Private Limited";
        $config['bank_name'] = "BANK NAME";
        
        $config['give_income_on_topup'] = "Yes";
        ####################### MODULE SETTING ############################## 
        $config['enable_topup'] = "No";
        $config['enable_repurchase'] = "Yes";
        $config['enable_coupon'] = "No";
        $config['enable_ad_incm'] = "No";
        $config['enable_survey'] = "No";
        $config['enable_recharge'] = "No";
        $config['enable_reward'] = "Yes";
        $config['enable_help_plan'] = "Yes";
        $config['enable_product'] = "Yes";
        $config['member_theme']='axxets/member/member_blue.css';
        $config['enable_upgrade']='No';
        $config['investment_mode'] = "MANUAL"; ## AUTO, EPIN, MANUAL
        $config['cron_minutes'] = "*/2"; 
        $config['cron_hours'] = "*"; 
        $config['cron_day'] = "*"; 
        $config['cron_month'] = "*"; 
        $config['cron_weekday'] = "*"; 
        $config['enable_investment'] = "No"; ## This will convert existing software to a investment plan software and will turn off many features.