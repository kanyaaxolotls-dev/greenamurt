$(document).ready(function(){
    $("#level1, #level2, #level3, #level4, #level5, #level6, #level7, #level8, #level9, #level10").hide();
    $("#all").hide();
    $('#SelctLevel').on('change', function() {
        $("#level1, #level2, #level3, #level4, #level5, #level6, #level7, #level8, #level9, #level10").hide();
        $("#all").hide();
        var selectedLevel = this.value;
        if (selectedLevel !== 'all') {
            $("#level" + selectedLevel).show();
        } else {
            $("#all").show();
        }
    });
});
