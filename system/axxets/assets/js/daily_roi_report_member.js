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
                                s_payment_date: $.trim($("#s_date").val()),
								e_payment_date: $.trim($("#e_date").val()),
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
                    s_payment_date: $.trim($("#s_date").val()),
                    e_payment_date: $.trim($("#e_date").val()),
                    status: $.trim($("#status option:selected").val())
                };
				
                coLumns = [
                    {"data": "userid", orderable: true},
                    {"data": "date", orderable: false},
                    {"data": "no_of_roi", orderable: false},
                    {"data": "amount", orderable: false},
                    {"data": "tax", orderable: false},
                    {"data": "admin_charges", orderable: false},
					{"data": "net_amount", orderable: false},
					{"data": "type", orderable: false},
                    {"data": "status", orderable: false}
					
                ];

            dtble = $('#dailyroilists').DataTable({
                processing: true,
                serverSide: true,
                bSort: true,
                searching: false,
                stateSave: true,
                ajax: {
                    url: BASEURL+"report/getalldailyroimember",
                    type: 'POST',
                    data: dataLoad,
                    error: function () {
                       // $(".dailyroilists-error").html("");
                        //$("#dailyroilists_processing").css("display", "none");
                    }

                },
                columns: coLumns
            });
        }