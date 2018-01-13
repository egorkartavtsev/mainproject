function validateDonorForm (){
    
    if(($("#number").val() === '') || ($("#brand").val()==='Выберите марку') || ($("#year").val()==='') || ($("#vin").val()==='')){
        alert("Заполните все обязательные поля");
    } else {
        $("#donorform").submit();
    }
    
    return false;
}

function filterDonorList(){
    ajax({
            url:"index.php?route=donor/list/filter&token=" + getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                param: $("#searchd").val()
            },
            success:function(data){
                $("#listDonors").html(data);
            }
        })
}
