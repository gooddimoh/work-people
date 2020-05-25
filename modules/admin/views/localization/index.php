<?php
/* @var $this yii\web\View */
    
    $this->beginJs();
	$this->title = 'Localization';
?>

<script>
    $(document).ready(function() {
//        alert("fq");
        var fileData;
        var dataTable = $("#tb_fileData");
        var selectApp = $('#selectApp');
        var selectFile = $('#selectFile');
        var tbBody = $("<tbody />");
        var arrayData;

        function langClass(langName){
            return "opt_"+langName;
        }

        function keyId(keyName){
            return "id_"+keyName.replace(/[^A-Za-z0-9]/gi,"");
        }
        
        function setNewCBLang(langName){
            var newId = langClass(langName);
            var newOption = $("<div class=\"checkbox\">\n\
                                <label>\n\
                                    <input class=\"cb_lang\" type=checkbox \n\
                                        id=\""+newId+"\" checked>"+langName+"</input>\n\
                                </label>&nbsp;&nbsp;\n\
                            </div>");
            $("#langList").append(newOption);
        }
        
        function getDataArray(data) {
            var keyList = [];
            for (var lang in data){
                for (var key in data['ru']) {
                    if (keyList[key] == undefined)
                        keyList[key] = [];
                    if (data[lang][key] == undefined) {
                        keyList[key].push("");
                    } else {
                        keyList[key].push(data[lang][key])
                    }
                }
            }
            return keyList;
        }

        function deleteParam(event){
//            if (confirm("Вы уверены?"))
                var tmpLine = $(event.target);
                tmpLine.closest("tr").remove();
//                event.target.closest("tr").remove();
//            console.log(event.target.parent.closest("tr"));
//            debugger;

        }


        function createTmpRow(num, name){
            var newLine = $("<tr />");
            newLine.append("<th>"+num+"</th><td>"+
                    "<div class=\"input-group mb-1\">" +
                    "<input value=\""+name+"\" type=\"text\" class=\"form-control input-sm\" disabled>" +
                    "<div class=\"input-group-append\">" +
                    "<a href=\"javascript:;\" class=\"text-danger pull-right\">"+
                    "<span class=\"glyphicon glyphicon glyphicon-remove delete-param\">&nbsp;</span></a>" +
                    "</div>"+
                    "</td>");
            return newLine;
        }

        function setData(data){
            // table id - tb_fileData
            arrayData = getDataArray(data);
            var tbHead = $("<thead />");
            var tbHeadRow = $("<tr />");

            var langList = [];
            var i = 0;
            
            dataTable.html("");//.empty();
            tbBody.html("");

            //head
            tbHead.append(tbHeadRow);
            tbHeadRow.append("<th width=\"50px;\">#</th>");
            tbHeadRow.append("<th style=\"width: 30%;\">Оригинальное</th>");
            for (var lang in data){
                tbHeadRow.append("<th class=\""+langClass(lang)+"\">"+lang+"</th>");
                langList.push(lang);
            }
            tbHeadRow.attr("id","tb_head");
            tbHead.appendTo(dataTable);
            
            //body 
            for (var key in arrayData){
                i++;
                var tmpRow = createTmpRow(i, key);
                for (var val in arrayData[key]){
                    var input_e = document.createElement("INPUT");
                    input_e.setAttribute("type", "text");
                    input_e.setAttribute("class", "form-control input-sm");
                    input_e.setAttribute("value", arrayData[key][val]);

                    var td_e = document.createElement("td");
                    td_e.setAttribute("class", langClass(langList[val]));
                    td_e.appendChild(input_e);

                    tmpRow.append(td_e);
                }
                tmpRow.appendTo(tbBody);
            }
            tbBody.appendTo(dataTable);
        }

        function getData(){
            var newData = [];
            var tbRows = tbBody.children("tr");
            var currStr = tbRows.first("tr");
            while(currStr.length>0){
                var tbRow = currStr.children();
                var curId = tbRow.first("td");
                curId = curId.next("td");
                var tmpKey = $.trim(curId.children().children("input").val());
//                newData[curId.text()] = [];
                newData[tmpKey] = [];
                var currElement = curId.next("td");
                while(currElement.length > 0){
//                    newData[curId.text()].push(currElement.children("input").val());
                    newData[tmpKey].push(currElement.children("input").val());

                    currElement = currElement.next("td");
                }
                currStr = currStr.next("tr");
            }
            return newData;
        }


        selectApp.change(function(){
            selectFile.empty();
            $.ajax('/admin/localization/index',{
                async: false,
                dataType: 'json',
                data: {
                    app: $(this).val()
                    }
                })
                .done(function(data){
                        $.each(data,function(key,val){
                            var newOption;
                            newOption = $("<option/>").appendTo(selectFile);
                            newOption.text(val).val(val);
                        });
                    });
            selectFile.change();
        });

        selectFile.change(function(){
//            fileData =
            $.ajax('/admin/localization/index',{
                async: false,
                dataType: 'json',
                data: {
                    app: selectApp.val(),
                    file: $(this).val()
                    }
                })
            .done(function(data){
                    fileData = data;
                    $("#langList").empty();
                    $.each(data, setNewCBLang);
                    setData(data);
                    $(".cb_lang").change(function(){
                        $("."+$(this).attr("id")).toggle(250);
                        });
                    $(".delete-param").click(deleteParam);
                    });
            });
            
        $(".add-string").click(function(){
            var newParamName = prompt("Название нового параметра");
            if (newParamName.length <= 0){
                alert("Название параметра не может быть пустым");
                return;
            }
            if (arrayData[newParamName] != undefined){
                alert("Ключ уже существует");
                return;
            }
            var newLine = createTmpRow(tbBody.children("tr").length+1, newParamName);
            for (var lang in fileData){
                newLine.append("<td class=\""+langClass(lang)+"\"><input class=\"form-control input-sm\" type='text' /></td>");
            }
//            tbBody.append(newLine);

            newLine.appendTo(tbBody);
            $(".delete-param").click(deleteParam);
        });
        
        $(".save-files").click(function(){
            var tbData = getData();
            var newData = {};
            var langList = [];

            for (var lang in fileData){
                langList.push(lang);
            }

            for (var i = 0; i < langList.length; i++){
                newData[langList[i]] = {};
                for (var key in tbData){
                    newData[langList[i]][key] = tbData[key][i];
                }
            }

            $.ajax('/admin/localization/index',{
                    async: false,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        app: selectApp.val(),
                        file: selectFile.val(),
                        fdata: JSON.stringify(newData, "", 4)
                    },
                    success: function(){
                        alert("Данные успешно сохранены")
                    }
                }
            )
//            console.log(JSON.stringify(newData));
        });

        selectApp.change();
    });
</script>
<?php $this->endJs(); ?>


<div class="panel panel-default">
	  <!-- Default panel contents -->
	  <div class="panel-heading">Языки</div>
	  <div class="panel-body">
		<div class="form-group">
			<label for="inputPassword" class="col-sm-2 control-label">Файлы приложния:</label>
			<div class="col-sm-10">
				<select class="form-control" id="selectApp">
                    <?php
                        foreach ($apps as $key) {
                            echo "<option value={$key}>{$key}</option>";
                    }
                    ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="inputPassword" class="col-sm-2 control-label">Файл переводов:</label>
			<div class="col-sm-10">
				<select class="form-control" id="selectFile">
				</select>
			</div>
		</div>
	
        <!-- отобразить столбцы(папки) в таблице: -->
        <div class="form-inline form-group" id="langList">
		</div>
        
		<button class="btn btn-success add-string">Добавить строку</button>
        <button class="btn btn-success save-files">Сохранить</button>
        
	  </div>

	  <!-- Table -->
	  <table class="table" id="tb_fileData">
	  </table>
        <div class="panel-body">
            <button class="btn btn-success add-string">Добавить строку</button>
            <button class="btn btn-success save-files">Сохранить</button>
        </div>
	</div>