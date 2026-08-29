  var dtble;
        $(document).ready(function () {
            if (typeof sessionStorage !== 'undefined') {
                var dataStored = sessionStorage.getItem('searchData');
                if (typeof dataStored !== 'undefined' && dataStored != null) {
                    dataStored = JSON.parse(dataStored);
                }
            }
			
            buildDataTable();

            $("#search,#res").on('click', function (e) {
				
                dtble.destroy();
                setTimeout(function () {
                    if (typeof sessionStorage !== 'undefined') {
                            var searchDataObj = {
                                s_payment_date: $.trim($("#s_payment_date").val()),
								e_payment_date: $.trim($("#e_payment_date").val()),
								status: $.trim($("#status option:selected").val())
                            }
							
                        sessionStorage.setItem('searchData', JSON.stringify(searchDataObj));
                    }
                    buildDataTable();
                }, 1000);

            });
			
        });


        function buildDataTable() {
            var dataLoad = {};
            var coLumns = [];

                dataLoad = {
                    s_payment_date: $.trim($("#s_payment_date").val()),
                    e_payment_date: $.trim($("#e_payment_date").val()),
                    status: $.trim($("#status option:selected").val())
                };
                coLumns = [
                    {"data": "userid", orderable: true},
                    {"data": "income_type", orderable: false},
                    {"data": "amount", orderable: false},
                    {"data": "roi", orderable: false},
                    {"data": "roi_frequency", orderable: false},
                    {"data": "roi_limit", orderable: false},
					{"data": "payment_date", orderable: false},
                    {"data": "status", orderable: false}
					
                ];

            dtble = $('#userlists').DataTable({
                processing: true,
                serverSide: true,
                bSort: true,
                searching: false,
                stateSave: true,
                ajax: {
                    url: BASEURL+"report/getallearningroi",
                    type: 'POST',
                    data: dataLoad,
                    error: function () {
                       // $(".userlists-error").html("");
                        //$("#userlists_processing").css("display", "none");
                    }

                },
                columns: coLumns
            });
        }