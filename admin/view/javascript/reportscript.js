$(document).ready(function(){
    $("#dsearch").on('input', function(){
        if($(this).val()===''){
            $("#searchbox").removeClass('open');
        } else {
            ajax({
                url: "index.php?route=report/donor/getVars&token="+getURLVar('token'),
                status: "stbox",
                method: "POST",
                data:{
                    request: $(this).val()
                },
                success:function(data){
                    $("#searchbox").addClass('open');
                    $("#searchResult").html(data);
                }
            })
            
        }
    })
});
    function getDonor($id, $val){
        $("#donorInfo").html('<div class="col-lg-12 alert alert-info">Подождите, информация обрабатывается сервером...</div>');
        ajax({
                url: "index.php?route=report/donor/getDonor&token="+getURLVar('token'),
                status: "stbox",
                method: "POST",
                data:{
                    request: $id
                },
                success:function(data){
                    $("#searchbox").removeClass('open');
                    $("#dsearch").val($val);
                    $("#donorInfo").html(data);
                }
            })
    };