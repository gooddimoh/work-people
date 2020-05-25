<?php
/* @var $this yii\web\View */
    
    $this->beginJs();
	$this->title = 'Handbook localization';
?>

<script>
    $(document).ready(function() {
        const requestLine = 'handbook';

        var selectFile = $("#selectFile");
        var btnSave = $(".save-files");
        var btnAdd = $(".add-string");

        var table = $(".table");
        var tbHead = $("#tHead");
        var tbBody = $("#tBody");

        var filesData = null;

        var langList = [];

        function getLangList(){
            langList = [];
            for (var lang in filesData){
                if (filesData.hasOwnProperty(lang)){
                    langList.push(lang);
                }
            }
        }

        function getFieldList(){
            var result = [];
            var tmpRecord = filesData["ru"][0];
            for (var key in tmpRecord){
                if (tmpRecord.hasOwnProperty(key)){
                    result.push(key);
                }
            }
            return result;
        }

        function setTableHead(){
            tbHead.empty();
            getLangList();
            var tmpLine = $("<tr>" +
                            "<th width=\"50px\">#</th>" +
                            "<th>Оригинал</th>" +
                            "</tr>");
            for(var i = 0; i < langList.length; i++){
                tmpLine.append("<th>"+langList[i]+"</th>");
            }
            tbHead.append(tmpLine);
        }

        function createTmpString(num, fields){
            var result = $("<tr />");
            var tmpGr= $("<div class=\"form-group\"></div>");
            result.append("<th>"+num+"</th>");
            for (var i = 0; i < fields.length; i++){
                tmpGr.append("<label class=\"form-control col-sm-12\">"+fields[i]+"</label>");
            }
            result.append($("<td/>").append(tmpGr));
            return result;
        }

        function createTmpTd(aData){
            var result = $("<div class=\"form-group\"/>");
            var fieldList = getFieldList();

            for (var i = 0; i < fieldList.length; i++){
                var tmpInput = $("<input type='text' class='form-control col-sm-12'>");
                var tmpVal = "";
                if (aData != undefined) {
                    tmpVal = aData[fieldList[i]];
                }
                tmpInput.val(tmpVal);
                result.append(tmpInput);
            }


            return $("<td />").append(result);
        }

        function setTableBody(){
            tbBody.empty();
            var fields = getFieldList();
            for (var i = 0; i < filesData['ru'].length; i++){
                var tmpStr = createTmpString(i + 1, fields);
                for(var j = 0; j < langList.length; j++){
                    tmpStr.append(createTmpTd(filesData[langList[j]][i]));
                }
                tbBody.append(tmpStr);
            }
        }

        function setData(){
            setTableHead();
            setTableBody();
        }

        function getData(){
            var resultData = {};
            getLangList();
            var fields = getFieldList();
            var tbLines = tbBody.children("tr");
            for (var i = 0; i < tbLines.length; i++){
                var tmpLine = tbLines[i];
                var tbCells = tmpLine.children;//("td");
                for(var j = 0; j < langList.length; j++) {
                    var tbInputs = tbCells[j+2].children[0].children;//("input");
                    var tmpRecordList = {};
//                    .first("input");
                    for (var k = 0; k < fields.length; k++){
                        var tmpInput = tbInputs[k];
                        tmpRecordList[fields[k]] = tmpInput.value;
//                        tmpInput = tmpInput.next("input");
                    }
                    if (resultData[langList[j]] == undefined){
                        resultData[langList[j]] = [];
                    }

                    resultData[langList[j]].push(tmpRecordList);
                }
            }
            return resultData;
        }

        selectFile.change(function(){
            $.ajax(requestLine,{
                async: false,
                dataType: 'json',
                data: {
                    file: selectFile.val()
                }
            })
                .done(function(data){
                    filesData = data;
                    setData();
                })
        });

        btnSave.click(function(){
            getData();
            var tmpData = getData();
            $.ajax(requestLine,{
                async: false,
                dataType: 'json',
                type: 'POST',
                data: {
                    app: 'handbook',
                    fname: selectFile.val(),
                    fdata: JSON.stringify(tmpData,"",4)
                },
                success: function(){
                    alert('Данные сохранены');
                }
            });
        });

        btnAdd.click(function(){
            var fields = getFieldList();
            var rowCount = tbBody.children("tr").length;
            var tmpStr = createTmpString(rowCount, fields);
            getLangList();
            for (var lang in langList){
                if (langList.hasOwnProperty(lang)){
                    tmpStr.append(createTmpTd(undefined));
                }
            }
            tbBody.append(tmpStr);
        });

        selectFile.change();
    });
</script>
<?php $this->endJs(); ?>


<div class="panel panel-default">
    <div class="panel-heading">Единицы измерения</div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-sm-2 control-label">Файл переводов:</label>
            <div class="col-sm-10">
                <select class="form-control" id="selectFile">
                    <?php
                        foreach ($files as $file){
                            echo "<option value=\"{$file}\">{$file}</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
        <button class="btn btn-success add-string">Добавить строку</button>
        <button class="btn btn-success save-files">Сохранить</button>
    </div>
    <table class="table">
        <thead id="tHead">
        </thead>
        <tbody id="tBody">
        </tbody>
    </table>
    <div class="panel-body">
        <button class="btn btn-success add-string">Добавить строку</button>
        <button class="btn btn-success save-files">Сохранить</button>
    </div>
</div>