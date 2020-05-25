var imageLoader = function () {
    var settings = {
        maxWidth: 1024,
        maxHeight: 768
    };
    var isSupported = function() {
        try {
            var elem;
            if (typeof FileReader !== "function" || typeof FileReader.prototype.readAsDataURL !== "function") {
                return false;
            }
            elem = document.createElement( 'canvas' );
            if (!elem.getContext || !elem.getContext('2d') || !elem.toDataURL) {
                return false;
            }
        } catch(e) {
            return false;
        }
        return true;
    };
    var supported = isSupported();
    if(!supported) {
        alert("Error! Image resizing is not supported on your browser.");
        console.log("Error! Image resizing is not supported on your browser.");
        return null;
    }

    var prepareImage = function (file, image) {
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        var ratio = Math.min(settings.maxWidth / image.width, settings.maxHeight / image.height, 1);
        var width = Math.round(image.width * ratio);
        var height = Math.round(image.height * ratio);
        var src = null;

        canvas.width = width;
        canvas.height = height;
        context.drawImage(image, 0, 0, width, height);

        src = canvas.toDataURL(file.type);

        return src;
    };

    var onChange = function(input) {
        return new Promise(function(resolve, reject) {
            var images = [];
            var countFiles = input.files.length;

            var createReader = function(file) {
                var reader = new FileReader();

                reader.onload = function (event) {
                    var image = new Image();

                    image.onload = function (event) {
                        var src = prepareImage(file, image);
                        images.push({
                            name: file.name,
                            type: file.type,
                            src: src
                        });
                        if (images.length == countFiles) {
                            resolve(images);
                        }
                    };
                    image.onerror = function () {
                        var msg = file.name + ' does not look like a valid image';
                        console.log(msg);
                        reject(msg);
                    };
                    image.src = event.target.result;
                };
                reader.readAsDataURL(file);
            };

            for (var i = 0; i < input.files.length; i ++) {
                createReader(input.files[i]);
                console.log(input.files[i]);
            }
        });
    };

    var getHtmlForm = function (image, key) {


        var form = function () {
            var wrap = $.parseHTML('<div class="article_image" id="article-image-'+key+'" data-name="'+image.name+'"></div>');
            var trashLink = $.parseHTML('<div class="article-img-remove-button-new"><a class="text-danger pull-right" id="article-img-remove-button-'+key+'" href="javascript:void(0);"><i class="fa fa-remove"></i></a></div>');
            var img = document.createElement('img');
            $(img).attr('src', image.src);
            // $(img).attr('style', 'max-width:600px;');
            var inputSrc = $.parseHTML('<input type="hidden" id="image-'+key+'-src" name="Image['+key+'][src]" class="form-control" value="'+image.src+'" aria-invalid="false">');
            var inputName = $.parseHTML('\
                <div class="form-group required">\
                    <input type="text" id="image-'+key+'-name" name="Image['+key+'][name]" class="form-control image-input-show" value="'+image.name+'" aria-invalid="false">\
                    <div class="help-block"></div>\
                </div>\
            ');
            var inputPathName = $.parseHTML('<input type="hidden" id="image-'+key+'-path_name" name="Image['+key+'][path_name]" class="form-control" value="'+image.name+'" aria-invalid="false">');
            var btnBlock = $.parseHTML('\
                <div class="main-images-btn-block">\
                    Показать:&nbsp;\
                    <a class="btn btn-default btn_main_image_list" href="javascript:void(0);">Список</a>&nbsp;\
                    <a class="btn btn-default btn_main_image" href="javascript:void(0);">Просмотр</a>\
                </div>\
            ');
            
            $(trashLink).appendTo(wrap);
            $(img).appendTo(wrap);
            $(inputSrc).appendTo(wrap);
            $(inputName).appendTo(wrap);
            $(inputPathName).appendTo(wrap);
            $(btnBlock).appendTo(wrap);
            return wrap;
        };
        return form();

    };

    return {
        onChange: onChange,
        getHtmlForm: getHtmlForm
    };

};