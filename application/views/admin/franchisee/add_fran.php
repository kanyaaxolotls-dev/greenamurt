
<script type="text/javascript">
    function check_user() {
        var data = $("#username").val();
        $.ajax({
            url: "<?php echo site_url('cron/check_user') ?>",
            method: "POST",
            data: {
                user: data,
            },
            success: function (response) {
                document.getElementById("result").innerHTML = response;
            },
        });
    }
</script>
<script type="text/javascript" src="<?php echo base_url('axxets/countries.js') ?>"></script>
<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8"> 
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div> 
            </div>  
        <div class="card-body">
            <?php echo form_open() ?>
            <div class="row">
                <div class="col-sm-6">
                    <label>Pick-up Centre / Dealer Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-alternative" required name="name" placeholder="Enter Dealer Name">
                </div>
                <div class="col-sm-6">
                    <label>Pick-up Centre Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-alternative" required name="username" id="username" placeholder="Pick-up Centre Username"><span id="result"></span>
                    <a href="javascript:;" onclick="check_user()" style="font-size: 10px; color: #f00">Check Availability &rarr;</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>GST Number</label>
                    <input type="text" class="form-control form-control-alternative" name="gstin" placeholder="Enter GST Number">
                </div>
                <div class="col-sm-6">
                    <label>Commision <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-alternative" name="cmsn" placeholder="Enter Commision" required>
                </div>
                <div class="col-sm-6 mt-2">
                    <label>Store / Business Name</label>
                    <input type="text" class="form-control form-control-alternative" name="business_name" placeholder="Enter Business Name">
                </div>
                <div class="col-sm-6 mt-2">
                    <label>Email ID</label>
                    <input type="text" class="form-control form-control-alternative" name="email" placeholder="Enter Email ID">
                </div>
                <div class="col-sm-6 mt-2">
                    <label>Phone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-alternative" name="phone" placeholder="Enter Phone">
                    <input type="hidden" class="form-control form-control-alternative" name="country" value="India">
                </div>
                <div class="col-sm-6 mt-2">
                    <label for="inputLevel">Pick-up Centre Level <span class="text-danger">*</span></label>
                    <select class="form-control" id="inputLevel" name="level"  required>
                        <option value="" disabled selected>-- select one -- </option>
                        <option value="1">State</option>
                        <option value="2">District</option>
                        <option value="3">Homeshopee</option>
                        <option value="4">Company shopee</option>
                    </select>
                </div>
                <div class="col-sm-6 mt-2">
                    <label for="inputState">State <span class="text-danger">*</span></label>
                    <select class="form-control" id="inputState" name="state">
                        <option value="SelectState">Select State</option>
                        <option value="Andra Pradesh">Andra Pradesh</option>
                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                        <option value="Assam">Assam</option>
                        <option value="Bihar">Bihar</option>
                        <option value="Chhattisgarh">Chhattisgarh</option>
                        <option value="Goa">Goa</option>
                        <option value="Gujarat">Gujarat</option>
                        <option value="Haryana">Haryana</option>
                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                        <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                        <option value="Jharkhand">Jharkhand</option>
                        <option value="Karnataka">Karnataka</option>
                        <option value="Kerala">Kerala</option>
                        <option value="Madya Pradesh">Madya Pradesh</option>
                        <option value="Maharashtra">Maharashtra</option>
                        <option value="Manipur">Manipur</option>
                        <option value="Meghalaya">Meghalaya</option>
                        <option value="Mizoram">Mizoram</option>
                        <option value="Nagaland">Nagaland</option>
                        <option value="Orissa">Orissa</option>
                        <option value="Punjab">Punjab</option>
                        <option value="Rajasthan">Rajasthan</option>
                        <option value="Sikkim">Sikkim</option>
                        <option value="Tamil Nadu">Tamil Nadu</option>
                        <option value="Telangana">Telangana</option>
                        <option value="Tripura">Tripura</option>
                        <option value="Uttaranchal">Uttaranchal</option>
                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                        <option value="West Bengal">West Bengal</option>
                        <option disabled style="background-color:#aaa; color:#fff">UNION Territories</option>
                        <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                        <option value="Chandigarh">Chandigarh</option>
                        <option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
                        <option value="Daman and Diu">Daman and Diu</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Lakshadeep">Lakshadeep</option>
                        <option value="Pondicherry">Pondicherry</option>
                    </select>
                </div>
                <div class="col-sm-6 mt-2">
                    <label for="inputDistrict">District <span class="text-danger">*</span></label>
                    <select class="form-control" id="inputDistrict" name="district">
                        <option value="">-- select one -- </option>
                    </select>
                </div>
                <div class="col-sm-6 mt-2">
                    <label>Taluka/Tehsil <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-alternative" name="taluka" placeholder="Enter Taluka">
                </div>
                <div class="col-sm-6 mt-2">
                    <label>Address</label>
                    <input type="text" class="form-control form-control-alternative" name="address" placeholder="Enter Address">
                </div>

                <div class="col-sm-6 mt-2" style="display: none;" id="districtFranDiv">
                    <label for="inputLevel">Select District Franchise <span class="text-danger">*</span></label>
                    <select class="form-control" id="under_district_fran" name="under_district_fran">
                        <option value="" disabled selected>-- select one -- </option>
                    
                    </select>
                </div>

                <div class="col-sm-6 mt-2"><br/>
                    <input type="submit" class="btn btn-success" value="Create" onclick="this.value='Creating..'">
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script>
    var AndraPradesh = ["Anantapur","Chittoor","East Godavari","Guntur","Kadapa","Krishna","Kurnool","Prakasam","Nellore","Srikakulam","Visakhapatnam","Vizianagaram","West Godavari"];
    var ArunachalPradesh = ["Anjaw","Changlang","Dibang Valley","East Kameng","East Siang","Kra Daadi","Kurung Kumey","Lohit","Longding","Lower Dibang Valley","Lower Subansiri","Namsai","Papum Pare","Siang","Tawang","Tirap","Upper Siang","Upper Subansiri","West Kameng","West Siang","Itanagar"];
    var Assam = ["Baksa","Barpeta","Biswanath","Bongaigaon","Cachar","Charaideo","Chirang","Darrang","Dhemaji","Dhubri","Dibrugarh","Goalpara","Golaghat","Hailakandi","Hojai","Jorhat","Kamrup Metropolitan","Kamrup (Rural)","Karbi Anglong","Karimganj","Kokrajhar","Lakhimpur","Majuli","Morigaon","Nagaon","Nalbari","Dima Hasao","Sivasagar","Sonitpur","South Salmara Mankachar","Tinsukia","Udalguri","West Karbi Anglong"];
    var Bihar = ["Araria","Arwal","Aurangabad","Banka","Begusarai","Bhagalpur","Bhojpur","Buxar","Darbhanga","East Champaran","Gaya","Gopalganj","Jamui","Jehanabad","Kaimur","Katihar","Khagaria","Kishanganj","Lakhisarai","Madhepura","Madhubani","Munger","Muzaffarpur","Nalanda","Nawada","Patna","Purnia","Rohtas","Saharsa","Samastipur","Saran","Sheikhpura","Sheohar","Sitamarhi","Siwan","Supaul","Vaishali","West Champaran"];
    var Chhattisgarh = ["Balod","Baloda Bazar","Balrampur","Bastar","Bemetara","Bijapur","Bilaspur","Dantewada","Dhamtari","Durg","Gariaband","Janjgir Champa","Jashpur","Kabirdham","Kanker","Kondagaon","Korba","Koriya","Mahasamund","Mungeli","Narayanpur","Raigarh","Raipur","Rajnandgaon","Sukma","Surajpur","Surguja"];
    var Goa = ["North Goa","South Goa"];
    var Gujarat = ["Ahmedabad","Amreli","Anand","Aravalli","Banaskantha","Bharuch","Bhavnagar","Botad","Chhota Udaipur","Dahod","Dang","Devbhoomi Dwarka","Gandhinagar","Gir Somnath","Jamnagar","Junagadh","Kheda","Kutch","Mahisagar","Mehsana","Morbi","Narmada","Navsari","Panchmahal","Patan","Porbandar","Rajkot","Sabarkantha","Surat","Surendranagar","Tapi","Vadodara","Valsad"];
    var Haryana = ["Ambala","Bhiwani","Charkhi Dadri","Faridabad","Fatehabad","Gurugram","Hisar","Jhajjar","Jind","Kaithal","Karnal","Kurukshetra","Mahendragarh","Mewat","Palwal","Panchkula","Panipat","Rewari","Rohtak","Sirsa","Sonipat","Yamunanagar"];
    var HimachalPradesh = ["Bilaspur","Chamba","Hamirpur","Kangra","Kinnaur","Kullu","Lahaul Spiti","Mandi","Shimla","Sirmaur","Solan","Una"];
    var JammuKashmir = ["Anantnag","Bandipora","Baramulla","Budgam","Doda","Ganderbal","Jammu","Kargil","Kathua","Kishtwar","Kulgam","Kupwara","Leh","Poonch","Pulwama","Rajouri","Ramban","Reasi","Samba","Shopian","Srinagar","Udhampur"];
    var Jharkhand = ["Bokaro","Chatra","Deoghar","Dhanbad","Dumka","East Singhbhum","Garhwa","Giridih","Godda","Gumla","Hazaribagh","Jamtara","Khunti","Koderma","Latehar","Lohardaga","Pakur","Palamu","Ramgarh","Ranchi","Sahebganj","Seraikela Kharsawan","Simdega","West Singhbhum"];
    var Karnataka = ["Bagalkot","Bangalore Rural","Bangalore Urban","Belgaum","Bellary","Bidar","Vijayapura","Chamarajanagar","Chikkaballapur","Chikkamagaluru","Chitradurga","Dakshina Kannada","Davanagere","Dharwad","Gadag","Gulbarga","Hassan","Haveri","Kodagu","Kolar","Koppal","Mandya","Mysore","Raichur","Ramanagara","Shimoga","Tumkur","Udupi","Uttara Kannada","Yadgir"];
    var Kerala = ["Alappuzha","Ernakulam","Idukki","Kannur","Kasaragod","Kollam","Kottayam","Kozhikode","Malappuram","Palakkad","Pathanamthitta","Thiruvananthapuram","Thrissur","Wayanad"];
    var MadhyaPradesh = ["Agar Malwa","Alirajpur","Anuppur","Ashoknagar","Balaghat","Barwani","Betul","Bhind","Bhopal","Burhanpur","Chhatarpur","Chhindwara","Damoh","Datia","Dewas","Dhar","Dindori","Guna","Gwalior","Harda","Hoshangabad","Indore","Jabalpur","Jhabua","Katni","Khandwa","Khargone","Mandla","Mandsaur","Morena","Narsinghpur","Neemuch","Panna","Raisen","Rajgarh","Ratlam","Rewa","Sagar","Satna",
    "Sehore","Seoni","Shahdol","Shajapur","Sheopur","Shivpuri","Sidhi","Singrauli","Tikamgarh","Ujjain","Umaria","Vidisha"];
    var Maharashtra = ["Ahmednagar","Akola","Amravati","Aurangabad","Beed","Bhandara","Buldhana","Chandrapur","Dhule","Gadchiroli","Gondia","Hingoli","Jalgaon","Jalna","Kolhapur","Latur","Mumbai City","Mumbai Suburban","Nagpur","Nanded","Nandurbar","Nashik","Osmanabad","Palghar","Parbhani","Pune","Raigad","Ratnagiri","Sangli","Satara","Sindhudurg","Solapur","Thane","Wardha","Washim","Yavatmal"];
    var Manipur = ["Bishnupur","Chandel","Churachandpur","Imphal East","Imphal West","Jiribam","Kakching","Kamjong","Kangpokpi","Noney","Pherzawl","Senapati","Tamenglong","Tengnoupal","Thoubal","Ukhrul"];
    var Meghalaya = ["East Garo Hills","East Jaintia Hills","East Khasi Hills","North Garo Hills","Ri Bhoi","South Garo Hills","South West Garo Hills","South West Khasi Hills","West Garo Hills","West Jaintia Hills","West Khasi Hills"];
    var Mizoram = ["Aizawl","Champhai","Kolasib","Lawngtlai","Lunglei","Mamit","Saiha","Serchhip","Aizawl","Champhai","Kolasib","Lawngtlai","Lunglei","Mamit","Saiha","Serchhip"];
    var Nagaland = ["Dimapur","Kiphire","Kohima","Longleng","Mokokchung","Mon","Peren","Phek","Tuensang","Wokha","Zunheboto"];
    var Odisha = ["Angul","Balangir","Balasore","Bargarh","Bhadrak","Boudh","Cuttack","Debagarh","Dhenkanal","Gajapati","Ganjam","Jagatsinghpur","Jajpur","Jharsuguda","Kalahandi","Kandhamal","Kendrapara","Kendujhar","Khordha","Koraput","Malkangiri","Mayurbhanj","Nabarangpur","Nayagarh","Nuapada","Puri","Rayagada","Sambalpur","Subarnapur","Sundergarh"];
    var Punjab = ["Amritsar","Barnala","Bathinda","Faridkot","Fatehgarh Sahib","Fazilka","Firozpur","Gurdaspur","Hoshiarpur","Jalandhar","Kapurthala","Ludhiana","Mansa","Moga","Mohali","Muktsar","Pathankot","Patiala","Rupnagar","Sangrur","Shaheed Bhagat Singh Nagar","Tarn Taran"];
    var Rajasthan = ["Ajmer","Alwar","Banswara","Baran","Barmer","Bharatpur","Bhilwara","Bikaner","Bundi","Chittorgarh","Churu","Dausa","Dholpur","Dungarpur","Ganganagar","Hanumangarh","Jaipur","Jaisalmer","Jalore","Jhalawar","Jhunjhunu","Jodhpur","Karauli","Kota","Nagaur","Pali","Pratapgarh","Rajsamand","Sawai Madhopur","Sikar","Sirohi","Tonk","Udaipur"];
    var Sikkim = ["East Sikkim","North Sikkim","South Sikkim","West Sikkim"];
    var TamilNadu = ["Ariyalur","Chennai","Coimbatore","Cuddalore","Dharmapuri","Dindigul","Erode","Kanchipuram","Kanyakumari","Karur","Krishnagiri","Madurai","Nagapattinam","Namakkal","Nilgiris","Perambalur","Pudukkottai","Ramanathapuram","Salem","Sivaganga","Thanjavur","Theni","Thoothukudi","Tiruchirappalli","Tirunelveli","Tiruppur","Tiruvallur","Tiruvannamalai","Tiruvarur","Vellore","Viluppuram","Virudhunagar"];
    var Telangana = ["Adilabad","Bhadradri Kothagudem","Hyderabad","Jagtial","Jangaon","Jayashankar","Jogulamba","Kamareddy","Karimnagar","Khammam","Komaram Bheem","Mahabubabad","Mahbubnagar","Mancherial","Medak","Medchal","Nagarkurnool","Nalgonda","Nirmal","Nizamabad","Peddapalli","Rajanna Sircilla","Ranga Reddy","Sangareddy","Siddipet","Suryapet","Vikarabad","Wanaparthy","Warangal Rural","Warangal Urban","Yadadri Bhuvanagiri"];
    var Tripura = ["Dhalai","Gomati","Khowai","North Tripura","Sepahijala","South Tripura","Unakoti","West Tripura"];
    var UttarPradesh = ["Agra","Aligarh","Allahabad","Ambedkar Nagar","Amethi","Amroha","Auraiya","Azamgarh","Baghpat","Bahraich","Ballia","Balrampur","Banda","Barabanki","Bareilly","Basti","Bhadohi","Bijnor","Budaun","Bulandshahr","Chandauli","Chitrakoot","Deoria","Etah","Etawah","Faizabad","Farrukhabad","Fatehpur","Firozabad","Gautam Buddha Nagar","Ghaziabad","Ghazipur","Gonda","Gorakhpur","Hamirpur","Hapur","Hardoi","Hathras","Jalaun","Jaunpur","Jhansi","Kannauj","Kanpur Dehat","Kanpur Nagar","Kasganj","Kaushambi","Kheri","Kushinagar","Lalitpur","Lucknow","Maharajganj","Mahoba","Mainpuri","Mathura","Mau","Meerut","Mirzapur","Moradabad","Muzaffarnagar","Pilibhit","Pratapgarh","Raebareli","Rampur","Saharanpur","Sambhal","Sant Kabir Nagar","Shahjahanpur","Shamli","Shravasti","Siddharthnagar","Sitapur","Sonbhadra","Sultanpur","Unnao","Varanasi"];
    var Uttarakhand  = ["Almora","Bageshwar","Chamoli","Champawat","Dehradun","Haridwar","Nainital","Pauri","Pithoragarh","Rudraprayag","Tehri","Udham Singh Nagar","Uttarkashi"];
    var WestBengal = ["Alipurduar","Bankura","Birbhum","Cooch Behar","Dakshin Dinajpur","Darjeeling","Hooghly","Howrah","Jalpaiguri","Jhargram","Kalimpong","Kolkata","Malda","Murshidabad","Nadia","North 24 Parganas","Paschim Bardhaman","Paschim Medinipur","Purba Bardhaman","Purba Medinipur","Purulia","South 24 Parganas","Uttar Dinajpur"];
    var AndamanNicobar = ["Nicobar","North Middle Andaman","South Andaman"];
    var Chandigarh = ["Chandigarh"];
    var DadraHaveli = ["Dadra Nagar Haveli"];
    var DamanDiu = ["Daman","Diu"];
    var Delhi = ["Central Delhi","East Delhi","New Delhi","North Delhi","North East Delhi","North West Delhi","Shahdara","South Delhi","South East Delhi","South West Delhi","West Delhi"];
    var Lakshadweep = ["Lakshadweep"];
    var Puducherry = ["Karaikal","Mahe","Puducherry","Yanam"];
    
    
    $("#inputState").change(function(){
      var StateSelected = $(this).val();
      var optionsList;
      var htmlString = "";
    
      switch (StateSelected) {
        case "Andra Pradesh":
            optionsList = AndraPradesh;
            break;
        case "Arunachal Pradesh":
            optionsList = ArunachalPradesh;
            break;
        case "Assam":
            optionsList = Assam;
            break;
        case "Bihar":
            optionsList = Bihar;
            break;
        case "Chhattisgarh":
            optionsList = Chhattisgarh;
            break;
        case "Goa":
            optionsList = Goa;
            break;
        case  "Gujarat":
            optionsList = Gujarat;
            break;
        case "Haryana":
            optionsList = Haryana;
            break;
        case "Himachal Pradesh":
            optionsList = HimachalPradesh;
            break;
        case "Jammu and Kashmir":
            optionsList = JammuKashmir;
            break;
        case "Jharkhand":
            optionsList = Jharkhand;
            break;
        case  "Karnataka":
            optionsList = Karnataka;
            break;
        case "Kerala":
            optionsList = Kerala;
            break;
        case  "Madya Pradesh":
            optionsList = MadhyaPradesh;
            break;
        case "Maharashtra":
            optionsList = Maharashtra;
            break;
        case  "Manipur":
            optionsList = Manipur;
            break;
        case "Meghalaya":
            optionsList = Meghalaya ;
            break;
        case  "Mizoram":
            optionsList = Mizoram;
            break;
        case "Nagaland":
            optionsList = Nagaland;
            break;
        case  "Orissa":
            optionsList = Orissa;
            break;
        case "Punjab":
            optionsList = Punjab;
            break;
        case  "Rajasthan":
            optionsList = Rajasthan;
            break;
        case "Sikkim":
            optionsList = Sikkim;
            break;
        case  "Tamil Nadu":
            optionsList = TamilNadu;
            break;
        case  "Telangana":
            optionsList = Telangana;
            break;
        case "Tripura":
            optionsList = Tripura ;
            break;
        case  "Uttaranchal":
            optionsList = Uttaranchal;
            break;
        case  "Uttar Pradesh":
            optionsList = UttarPradesh;
            break;
        case "West Bengal":
            optionsList = WestBengal;
            break;
        case  "Andaman and Nicobar Islands":
            optionsList = AndamanNicobar;
            break;
        case "Chandigarh":
            optionsList = Chandigarh;
            break;
        case  "Dadar and Nagar Haveli":
            optionsList = DadraHaveli;
            break;
        case "Daman and Diu":
            optionsList = DamanDiu;
            break;
        case  "Delhi":
            optionsList = Delhi;
            break;
        case "Lakshadeep":
            optionsList = Lakshadeep ;
            break;
        case  "Pondicherry":
            optionsList = Pondicherry;
            break;
    }
      for(var i = 0; i < optionsList.length; i++){
        htmlString = htmlString+"<option value='"+ optionsList[i] +"'>"+ optionsList[i] +"</option>";
      }
      $("#inputDistrict").html(htmlString);
    
    });
</script>

<script>

    $("#inputDistrict").on("change", function () {

    var districtSelected = $(this).val();
    var inputLevelSelected = $("#inputLevel").val();

    if (parseInt(inputLevelSelected) === 3 && districtSelected) {

        $("#districtFranDiv").show();

        $.ajax({
            url: "<?php echo site_url('Adm_franchisee/get_franchise_list'); ?>",
            type: "POST",
            data: {
                district_name: districtSelected,
                level: 2
            },
            dataType: "json",
            success: function (response) {

                $("#under_district_fran").html(
                    '<option value="" disabled selected>-- select one --</option>'
                );

                if (response.length > 0) {
                    $.each(response, function (key, value) {
                        $("#under_district_fran").append(
                            '<option value="' + value.id + '">' + value.business_name + '</option>'
                        );
                    });
                } else {
                    $("#under_district_fran").append(
                        '<option value="">No franchise found</option>'
                    );
                }
            },
            error: function () {
                alert("AJAX error while loading franchises.");
            }
        });

    } else {
        $("#districtFranDiv").hide();
        $("#under_district_fran").html(
            '<option value="" disabled selected>-- select one --</option>'
        );
    }
});
</script>
