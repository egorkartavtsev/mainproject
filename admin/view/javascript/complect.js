    var count = 0;
    var complect = [];
    var prodList = [];
    var prodCount = 0;
    var openedAJAXes = false;
    
    
    
    function addInput($token){
        
        $cont = '<tr id="c'+window.count+'">';
        $cont+= '<td id="accss'+window.count+'">';
        $cont+= document.getElementById('compl').value;
        $cont+= '</td>';
        $cont+= '<td>';
        $cont+= document.getElementById('complres').innerText;
        $cont+= '</td>';
        $cont+= '<td>';
        $cont+= '<button class="btn btn-danger" onclick="deleteInput(\'c'+window.count+'\', '+window.count+')"><i class="fa fa-minus fw"></button>';
        $cont+= '</td>';
        $cont+= '</tr>';
        window.complect[window.count] = document.getElementById('compl').value;
        window.count+=1;
        document.getElementById('complect').innerHTML += $cont;
    }
    
    function addAccss($token){
        $cont = '<tr id="c'+window.count+'">';
        $cont+= '<td id="accss'+window.count+'">';
        $cont+= document.getElementById('compl').value;
        $cont+= '</td>';
        $cont+= '<td>';
        $cont+= document.getElementById('complres').innerText;
        $cont+= '</td>';
        $cont+= '<td>';
        $cont+= '<button class="btn btn-danger" onclick="deleteAccss(\''+document.getElementById('compl').value+'\' \'c'+window.count+'\', '+window.count+', \''+$token+'\')"><i class="fa fa-minus fw"></button>';
        $cont+= '</td>';
        $cont+= '</tr>';
        window.complect[window.count] = document.getElementById('compl').value;
        window.count+=1;        
        document.getElementById('complect').innerHTML += $cont;
    }

    function deleteInput($id, $acc){
        document.getElementById($id).parentNode.removeChild(document.getElementById($id));
        window.complect[$acc] = '';
    }
    
    function writeoff($id, $token){
        ajax({
            url:"index.php?route=complect/complect/writeOff&token="+$token,
            statbox:"status",
            method:"POST",
            data:
            {
                id: $id
            },
            success:function(data){
                //document.getElementById('comp'+$id).parentNode.removeChild(document.getElementById('comp'+$id));
                document.getElementById('comp'+$id).innerHTML = data;
            }
        })
    }
    
    function deleteAccss($sku, $id, $n_comp, $token){
        ajax({
            url:"index.php?route=complect/complect/deleteAccss&token="+$token,
            statbox:"status",
            method:"POST",
            data:
            {
                accss: $sku,
                token: $token
            },
            success:function(data){
                document.getElementById($id).parentNode.removeChild(document.getElementById($id));
                window.complect[$n_comp] = '';
            }
        })
        
    }

    function XmlHttp()
    {
        var xmlhttp;
        try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
        catch(e)
        {
            try {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");} 
            catch (E) {xmlhttp = false;}
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined')
        {
            xmlhttp = new XMLHttpRequest();
        }
          return xmlhttp;
    }

    function ajax(param)
    {
                    if (window.XMLHttpRequest) req = new XmlHttp();
                    method=(!param.method ? "POST" : param.method.toUpperCase());

                    if(method=="GET")
                    {
                                   send=null;
                                   param.url=param.url+"&ajax=true";
                    }
                    else
                    {
                                   send="";
                                   for (var i in param.data) send+= i+"="+param.data[i]+"&";
                                   // send=send+"ajax=true"; // если хотите передать сообщение об успехе
                    }
                    window.openedAJAXes = true;
                    req.open(method, param.url, true);
                    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    req.send(send);
                    req.onreadystatechange = function(){
                        if(req.readyState !== 1){
                            window.openedAJAXes = false;
                        }
                        if (req.readyState == 4 && req.status == 200){ //если ответ положительный
                           if(param.success)param.success(req.responseText);
                        }
                    }
    }

    function hasQueries(){
        return window.openedAJAXes;
    }

    function create($token){
        ajax({
            url:"index.php?route=complect/complect/addComplect&token="+$token,
            statbox:"status",
            method:"POST",
            data:
            {
                complect: window.complect,
                heading: document.getElementById('heading-crt').value,
                name: document.getElementById('name').value,
                price: document.getElementById('price').value,
                whole: document.getElementById('whole').value,
                sale: document.getElementById('sale').value,
                token: $token
            },
            success:function(data){
                    document.getElementById('complect').innerHTML = data;
                    document.getElementById('name').value = '';
                    document.getElementById('price').value = '';
                    document.getElementById('heading').value = '';
                    document.getElementById('compl').value = '';
                    document.getElementById('result_box').innerHTML = '';
                    document.getElementById('complres').innerHTML = '';
                    window.complect = [];
            }
        })
    }

    function validVin($input, $stat_b, $token){
        ajax({
            url:"index.php?route=complect/complect/validVin&token="+$token,
            statbox:"status",
            method:"POST",
            data:
            {
                vin: document.getElementById($input).value,
                token: $token
            },
            success:function(data){
                    document.getElementById($stat_b).innerHTML = data;
                    if($stat_b==='result_box'){
                        document.getElementById('name').value = data;
                    }
            }
        })
    }
    
     function findprod(){
        ajax({
            url:"index.php?route=production/writeoff/findProd&token="+getURLVar('token'),
            statbox:"status",
            method:"POST",
            data:
            {
                vin: document.getElementById("vin").value,
                token: getURLVar('token')
            },
            success:function(data){
                    document.getElementById("prodinfo").innerHTML=data; 
                    $("#wo-form").hide("slow", function(){});
                    $("#statbox").innerHTML = '';
                    document.getElementById("client").value = '';
                    document.getElementById("city").value = '';
                    document.getElementById("quantity").value = '1';
                    document.getElementById("date").value = '';
            }
        })
    }

    function showinput($stock){
        $id = '#for'+$stock;
        $($id).toggle("slow", function(){});
    }
    
    function editComplect($token){
        ajax({
            url:"index.php?route=complect/complect/editComplect&token="+$token,
            statbox:"status",
            method:"POST",
            data:
            {
                complect: window.complect,
                id: document.getElementById('id').value,
                heading: document.getElementById('heading-crt').value,
                name: document.getElementById('name').value,
                price: document.getElementById('price').value,
                whole: document.getElementById('whole').value,
                sale: document.getElementById('sale').value,
                token: $token
            },
            success:function(data){
                    document.getElementById("statbox").innerHTML=data;
            }
        })
    }
    
    function sub_temp($div_id, $temp_id){
        jQuery.post("index.php?route=common/desctemp/netwTempl&token="+getURLVar('token'), {temp: jQuery("#"+$div_id).val(), temp_id: $temp_id});
        window.location.replace("index.php?route=common/desctemp&token="+getURLVar('token'));
    }
    
    function app_temp($token){
        jQuery.post("index.php?route=common/desctemp/apply&token="+$token,"temp="+encodeURIComponent(jQuery("#desctempl").val()));
        location.reload();
    }
    
    function showform(){
        $("#wo-form").toggle("slow", function(){});
        $("#formwo").show("slow", function(){});                
    }

    function findprod($token){
        ajax({
            url:"index.php?route=production/writeoff/findProd&token="+$token,
            statbox:"status",
            method:"POST",
            data:
            {
                vin: document.getElementById("vin").value,
                token: $token
            },
            success:function(data){
                    document.getElementById("prodinfo").innerHTML=data;
            }
        })
    }

    function saleprod($token){
        ajax({
            url:"index.php?route=common/write_off/saled&token="+$token,
            statbox:"status",
            method:"POST",
            data:
            {
                name: document.getElementById("name").innerText,
                quan: document.getElementById("quan").innerText,
                price: document.getElementById("price").innerText,
                location: document.getElementById("loc").innerText,
                client: document.getElementById("client").value,
                city: document.getElementById("city").value,
                quantity: document.getElementById("quantity").value,
                sku: document.getElementById("vin").value,
                date: document.getElementById("date").value,
                saleprice: document.getElementById("saleprice").value,
                reason: document.getElementById("reason").value
            },
            success:function(data){
                    document.getElementById("statbox").innerHTML=data; 
                    $("#formwo").toggle("slow", function(){});
                    $("#writeoff").toggle("slow", function(){});
            }
        })
    }

    function SubmitForm(){
        //document.forms['SubmitForm'].submit();
        document.getElementById('SubmitForm').onsubmit(function(){
            alert('Данные успешно обработаны!');
            location.reload();
        });
    }

    function addToList($id, $token) {
        if(window.prodList.indexOf($id)!=-1){
            alert('Этот продукт уже добавлен в список!');
        } else {
            $("#wo-form").show("slow", function(){});

             ajax({
                url:"index.php?route=production/writeoff/addToList&token="+$token,
                statbox:"status",
                method:"POST",
                data:
                {
                    vin: $id
                },
                success:function(data){
                    window.prodList[window.prodCount] = $id;
                    window.prodCount += 1;
                    $('#subm').show('slow', function(){});
                    document.getElementById("listProds").innerHTML+=data;
                }
            })
        }

    }

    function unsetElement($vin){
        window.prodList[window.prodList.indexOf($vin)] = 0;
        document.getElementById($vin).innerHTML = '';
        var element = document.getElementById($vin);
        element.parentNode.removeChild(element);
    }

    function refr(){
        setTimeout("document.location.reload()", 1000);
    }

    function validate($val, $match){
        var valid = document.getElementById($val).value;
        var matcher = document.getElementById($match).value;
        if(valid <= matcher){
            if(valid != '') {
                if(valid != 0){
                    $('#'+$val).css('box-shadow', '0px 0px 15px #8f5');
                    $('#subm').show('slow', function(){});
                } else {
                    $('#'+$val).css('box-shadow', '0px 0px 15px #f52');
                    $('#subm').hide('slow', function(){});
                }
            } else {
                $('#'+$val).css('box-shadow', '0px 0px 15px #f52');
                $('#subm').hide('slow', function(){});
            }
        } else {
            $('#'+$val).css('box-shadow', '0px 0px 15px #f52');
            $('#subm').hide('slow', function(){});
        }
    }
    
    function validateADD($token){
        document.getElementById('add-status-bar').innerHTML = '<div class="col-lg-12 alert alert-warning" id="process-bar"><p id="valInp">Проверка заполненных полей: <br></p></div>';
        if(document.getElementById('vin').value!==''){
            document.getElementById('valInp').innerHTML+='Внутренний номер - <b>ВВЕДЁН</b><br>';
            document.getElementById('valInp').innerHTML+='Марка - ';
            if(document.getElementById('brand').value!=='Выберите марку'){
                document.getElementById('valInp').innerHTML+='<b>OK</b><br>';
                document.getElementById('valInp').innerHTML+='Модель - ';
                if(document.getElementById('model').value!=='Выберите модель'){
                    document.getElementById('valInp').innerHTML+='<b>OK</b><br>';
                    document.getElementById('valInp').innerHTML+='Модельный ряд - ';
                    if(document.getElementById('model_row_id').value!=='Выберите модельный ряд'){
                        document.getElementById('valInp').innerHTML+='<b>OK</b><br>';
                        document.getElementById('valInp').innerHTML+='Подкатегория - ';
                        if(document.getElementById('podcat').value!==''){
                            document.getElementById('valInp').innerHTML+='<b>OK</b><br>';
                            document.getElementById('valInp').innerHTML+='Категория - ';
                            if(document.getElementById('category_id').value!==''){
                                document.getElementById('valInp').innerHTML+='<b>OK</b><br>';
                                document.getElementById('process-bar').innerHTML += '<p id="valVin">Проверка наличия внутреннего номера в базе...</p>';
                                ajax({
                                    url:"index.php?route=common/addprod/validateVin&token="+$token,
                                    statbox:"status",
                                    method:"POST",
                                    data:
                                    {
                                        vin: document.getElementById("vin").value,
                                    },
                                    success:function(data){
                                            if(data!=='0'){
                                                document.getElementById('valVin').innerHTML+=' <b>OK!</b>';
                                                document.getElementById('process-bar').innerHTML+='<p id="addProc">Добавление товара в базу...</p>';
                                                var formData = new FormData($("form[name='uploader']")[0]);
                                                var updateForm = new FormData($("form[name='updateForm']")[0]);
                                                $.ajax({
                                                    url:"index.php?route=common/addprod/addToList&token="+$token,
                                                    type: "POST",
                                                    data: formData,
                                                    success: function (msg) {
                                                        document.getElementById('addProc').innerHTML+=' <b>OK!</b>';
                                                        $(msg).insertBefore("#data-form");
                                                        document.getElementById('vin').value='';
                                                        document.getElementById('model1').innerHTML='';
                                                        document.getElementById('model_row').innerHTML='';
                                                        document.getElementById('brand').value='Выберите марку';
                                                        document.getElementById('cat').innerHTML='';
                                                        document.getElementById('podcat').value='';
                                                        document.getElementById('pcat_id').value='';
                                                        document.getElementById('photos').value='';
                                                        $('#upbut').show('slow', function(){});
                                                        $('#process-bar').hide('slow', function(){});
                                                        $("form[name='updateForm']").values = updateForm;
                                                    },
                                                    error: function(msg) {
                                                        alert('Ошибка!');
                                                    },
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false
                                                });
                                           } else {
                                               document.getElementById('valVin').innerHTML+= '<b>Такой внутренний номер уже присутствует в базе!</b>';
                                           }
                                    }
                                });
                            } else {
                                document.getElementById('valInp').innerHTML+='<b>ОШИБКА</b><br>';
                            }                            
                        } else {
                            document.getElementById('valInp').innerHTML+='<b>ОШИБКА</b><br>';
                        }
                    } else {
                        document.getElementById('valInp').innerHTML+='<b>ОШИБКА</b><br>';
                    }
                } else {
                    document.getElementById('valInp').innerHTML+='<b>ОШИБКА</b><br>';
                }
            } else {
                document.getElementById('valInp').innerHTML+='<b>ОШИБКА</b><br>';
            }
        } else {
            document.getElementById('valInp').innerHTML+='Внутренний номер - <b>НЕ ВВЕДЁН</b><br>';
        }
    }
    
    function sentToDB($token){
        
        var formData = new FormData($("form[name='updateForm']")[0]);
        $.ajax({
            url:"index.php?route=common/addprod/updateDB&token="+$token,
            type: "POST",
            data: formData,
            async: true,
            success: function (msg) {
                document.getElementById('data-form').innerHTML=msg;
                $('#upbut').hide('slow', function(){});
            },
            error: function(msg) {
                alert('Ошибка!');
            },
            cache: false,
            contentType: false,
            processData: false
        });   
    }
    
    function searchComplects($token){
        ajax({
                url:"index.php?route=complect/complect/searchComplects&token="+$token,
                statbox:"status",
                method:"POST",
                data:
                {
                    request: document.getElementById('nameComp').value
                },
                success:function(data){
                    document.getElementById("totalCompl").innerHTML=data;
                }
            })
    }
    
    function setComplect($id){
        var compl = document.getElementById("complect"+$id).value;
        if(compl === '3'){
            document.getElementById("compl"+$id).innerHTML='<input class="form-control" type="text" id="heading'+$id+'" oninput="tryCompl(\''+$id+'\')" name="info['+$id+'][heading]" placeholder="Введите головной товар">';
        } else {
            if (compl === '2') {
                document.getElementById("compl"+$id).innerHTML='<input class="form-control" type="hidden" name="info['+$id+'][heading]" value="create">';
                document.getElementById("compl"+$id).innerHTML+='<input class="form-control" type="text" name="info['+$id+'][compl_price]" placeholder="Введите цену комплекта">';
            } else {
                if (compl === '1') {
                    document.getElementById("compl"+$id).innerHTML='<input class="form-control" type="hidden" name="info['+$id+'][heading]" value="skip">';
                }
            }
        }
    }
    
    function cpb($pid){
        document.getElementById("cpb"+$pid).value = document.getElementById("inp-cpb"+$pid).value;
    }
    