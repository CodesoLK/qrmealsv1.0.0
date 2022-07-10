@extends('layouts.app', ['title' => __('Restaurants')])
@section('admin_title')
    {{__('Restaurants')}}
@endsection
@section('content')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Designer</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.1/cropper.css">
    
    <!-- JavaScript Bundle with Popper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/cropperjs@2.0.0-alpha.2/dist/cropper.js"></script>
    <script src="http://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer="true"></script>

    <style>
        .fieldset-template {
    border: 2px groove #ccc;
    padding: 0.875rem;
    overflow: hidden;
    height: initial;
    transition: all 0.3s linear;
    float: inherit !important;
}
.all-canvas .col {
    margin-bottom: 0.5rem;
}
.fieldset-template.close{
    height: 0px;
    padding: 0px 0.875rem;
    transition: all 0.3s linear;
}

.fieldset-template legend {
    margin-bottom: .5rem;
    line-height: inherit;
    float: inherit;
    width: inherit;
    padding: 0px 4px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
}

.template-container {
    background: #563d7c;
    height: 300px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-size: cover !important;
    background-repeat: no-repeat !important;
}

.template-real {
    border: 1px dashed rgb(255, 0, 0);
    width: 96%;
    height: 96%;
    position: relative;
}

.template-real>div {
    position: absolute;
    top: 0;
    left: 0;
    cursor: move;
    /* top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); */
}

.default-template-text {
    color: #fff;
    font-size: 1rem;
}
.submit-template-btn {
    margin-left: 10px;
}


.pageLoader{
    background: url('../../../images/loader.gif') no-repeat center center;
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: 9999999;
    background-color: #ffffff8c;
    background-size: 30%;
    display: none;
}

</style>

<div  class="pageLoader" id="pageLoader"></div>

<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

<div class="container-fluid mt--7">

        @if(count($errors) > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        <div class="row">
            <div class="col">
                <div class="card border-0">
                    <div class="card-body"  style="overflow-x:auto">
                        <div class="template-container">
                            <div class="template-real"></div>
                        </div>
                        {{-- <div class="layers mt-3">
                            <fieldset class="fieldset-template">
                                <legend>Layers</legend>
                                <table>
                                    <tbody class="layers-area">
                                    </tbody>
                                </table>
                            </fieldset>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('flyer.update', $template->id) }}" class="submit-template-form form-group mb-2 d-flex justify-content-between">
                            @csrf
                            @method('PUT')
                            <input type="text" name="template_name" placeholder="Template Name" class="form-control w-75" value="" required/>
                            <button class="btn btn-dark btn-sm submit-template-btn">Save Template</button>
                        </form>
                        
                        <div class="form-group mb-3">
                            <fieldset class="fieldset-template">
                                <legend>Layout</legend>
                                <div class="row mb-2">
                                    <div class="col">
                                        <label>Template Width (px)</label>
                                        <input type="number" class="form-control layout-width" />
                                    </div>
                                    <div class="col">
                                        <label>Template Height (px)</label>
                                        <input type="number" class="form-control layout-height" />
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary float-end layout-submit">Change</button>
                            </fieldset>
                        </div>

                        <div class="form-group mb-3">
                            <fieldset class="fieldset-template">
                                <legend>Theme</legend>
                                <div class="row mb-2">
                                    <div class="col">
                                        <label>Background Image</label>
                                        <input type="file" class="form-control layout-bg-image" accept="image/x-png,image/gif,image/jpeg" />
                                    </div>
                                    <div class="col">
                                        <label>Solid Color</label>
                                        <input type="color" class="form-control form-control-color layout-bg-solid" value="#563d7c" title="Choose your color">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary float-end layout-bg-reset">Reset</button>
                            </fieldset>
                        </div>

                        {{-- <div class="form-group mb-3">
                            <fieldset class="fieldset-template">
                                <legend>Add Layer</legend>
                                <div class="row mb-2">
                                    <div class="col">
                                        <h5 class="d-inline-block"><button class="badge rounded-pill text-bg-success border-0 add-layer-image">Image</button></h5>
                                        <h5 class="d-inline-block"><button class="badge rounded-pill text-bg-success border-0 add-layer-text">Text</button></h5>
                                    </div>
                                </div>
                            </fieldset>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="all-canvas"></div>


        <!-- Modal -->
        <div class="modal fade modal-lg" id="cropModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crop Template Background</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" class="bg-cropper"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary crop-modal-btn">Save</button>
                </div>
                </div>
            </div>
        </div>




        <!-- Modal -->
        <div class="modal fade" id="changeLayerName" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Layer Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="layer_name_change form-control" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary layer-name-change-btn">Save changes</button>
            </div>
            </div>
        </div>
        </div>


              

        @include('layouts.footers.auth')
    </div>

    <script>

        const fontCheck = new Set([
            // Windows 10
            'Arial', 'Arial Black', 'Bahnschrift', 'Calibri', 'Cambria', 'Cambria Math', 'Candara', 'Comic Sans MS', 'Consolas', 'Constantia', 'Corbel', 'Courier New', 'Ebrima', 'Franklin Gothic Medium', 'Gabriola', 'Gadugi', 'Georgia', 'HoloLens MDL2 Assets', 'Impact', 'Ink Free', 'Javanese Text', 'Leelawadee UI', 'Lucida Console', 'Lucida Sans Unicode', 'Malgun Gothic', 'Marlett', 'Microsoft Himalaya', 'Microsoft JhengHei', 'Microsoft New Tai Lue', 'Microsoft PhagsPa', 'Microsoft Sans Serif', 'Microsoft Tai Le', 'Microsoft YaHei', 'Microsoft Yi Baiti', 'MingLiU-ExtB', 'Mongolian Baiti', 'MS Gothic', 'MV Boli', 'Myanmar Text', 'Nirmala UI', 'Palatino Linotype', 'Segoe MDL2 Assets', 'Segoe Print', 'Segoe Script', 'Segoe UI', 'Segoe UI Historic', 'Segoe UI Emoji', 'Segoe UI Symbol', 'SimSun', 'Sitka', 'Sylfaen', 'Symbol', 'Tahoma', 'Times New Roman', 'Trebuchet MS', 'Verdana', 'Webdings', 'Wingdings', 'Yu Gothic',
            // macOS
            'American Typewriter', 'Andale Mono', 'Arial', 'Arial Black', 'Arial Narrow', 'Arial Rounded MT Bold', 'Arial Unicode MS', 'Avenir', 'Avenir Next', 'Avenir Next Condensed', 'Baskerville', 'Big Caslon', 'Bodoni 72', 'Bodoni 72 Oldstyle', 'Bodoni 72 Smallcaps', 'Bradley Hand', 'Brush Script MT', 'Chalkboard', 'Chalkboard SE', 'Chalkduster', 'Charter', 'Cochin', 'Comic Sans MS', 'Copperplate', 'Courier', 'Courier New', 'Didot', 'DIN Alternate', 'DIN Condensed', 'Futura', 'Geneva', 'Georgia', 'Gill Sans', 'Helvetica', 'Helvetica Neue', 'Herculanum', 'Hoefler Text', 'Impact', 'Lucida Grande', 'Luminari', 'Marker Felt', 'Menlo', 'Microsoft Sans Serif', 'Monaco', 'Noteworthy', 'Optima', 'Palatino', 'Papyrus', 'Phosphate', 'Rockwell', 'Savoye LET', 'SignPainter', 'Skia', 'Snell Roundhand', 'Tahoma', 'Times', 'Times New Roman', 'Trattatello', 'Trebuchet MS', 'Verdana', 'Zapfino',
            ].sort());

            (async() => {
            await document.fonts.ready;
            const fontAvailable = new Set();
            for (const font of fontCheck.values()) {
                if (document.fonts.check(`12px "${font}"`)) {
                fontAvailable.add(font);
                }
            }
            return [...fontAvailable.values()];
        })();


        function ezBSAlert (options) {
            var deferredObject = $.Deferred();
            var defaults = {
                type: "alert", //alert, prompt,confirm 
                modalSize: 'modal-sm', //modal-sm, modal-lg
                okButtonText: 'Ok',
                cancelButtonText: 'Cancel',
                yesButtonText: 'Yes',
                noButtonText: 'No',
                headerText: 'Attention',
                messageText: 'Message',
                alertType: 'default', //default, primary, success, info, warning, danger
                inputFieldType: 'text', //could ask for number,email,etc
            }
            $.extend(defaults, options);
        
            var _show = function(){
                var headClass = "navbar-default";
                switch (defaults.alertType) {
                    case "primary":
                        headClass = "alert-primary";
                        break;
                    case "success":
                        headClass = "alert-success";
                        break;
                    case "info":
                        headClass = "alert-info";
                        break;
                    case "warning":
                        headClass = "alert-warning";
                        break;
                    case "danger":
                        headClass = "alert-danger";
                        break;
                }
                $('BODY').append(
                    '<div id="ezAlerts" class="modal fade">' +
                    '<div class="modal-dialog" class="' + defaults.modalSize + '">' +
                    '<div class="modal-content">'+
                    '<div id="ezAlerts-header" class="modal-header ' + headClass + '"><h5 class="modal-title" id="ezAlerts-title">Modal title</h5></div>'+
                    '<div id="ezAlerts-body" class="modal-body">' +
                    '<div id="ezAlerts-message" ></div>' +
                    '</div>' +
                    '<div id="ezAlerts-footer" class="modal-footer">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $('.modal-header').css({
                    'padding': '15px 15px',
                    '-webkit-border-top-left-radius': '5px',
                    '-webkit-border-top-right-radius': '5px',
                    '-moz-border-radius-topleft': '5px',
                    '-moz-border-radius-topright': '5px',
                    'border-top-left-radius': '5px',
                    'border-top-right-radius': '5px'
                });
            
                $('#ezAlerts-title').text(defaults.headerText);
                $('#ezAlerts-message').html(defaults.messageText);

                var keyb = "false", backd = "static";
                var calbackParam = "";
                switch (defaults.type) {
                    case 'alert':
                        keyb = "true";
                        backd = "true";
                        $('#ezAlerts-footer').html('<button class="btn btn-' + defaults.alertType + '">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
                            calbackParam = true;
                            $('#ezAlerts').modal('hide');
                        });
                        break;
                    case 'confirm':
                        var btnhtml = '<button id="ezok-btn" class="btn btn-primary">' + defaults.yesButtonText + '</button>';
                        if (defaults.noButtonText && defaults.noButtonText.length > 0) {
                            btnhtml += '<button id="ezclose-btn" class="btn btn-danger">' + defaults.noButtonText + '</button>';
                        }
                        $('#ezAlerts-footer').html(btnhtml).on('click', 'button', function (e) {
                                if (e.target.id === 'ezok-btn') {
                                    calbackParam = true;
                                    $('#ezAlerts').modal('hide');
                                } else if (e.target.id === 'ezclose-btn') {
                                    calbackParam = false;
                                    $('#ezAlerts').modal('hide');
                                }
                            });
                        break;
                    case 'prompt':
                        $('#ezAlerts-message').html(defaults.messageText + '<br /><br /><div class="form-group"><input type="' + defaults.inputFieldType + '" class="form-control" id="prompt" /></div>');
                        $('#ezAlerts-footer').html('<button class="btn btn-primary">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
                            calbackParam = $('#prompt').val();
                            $('#ezAlerts').modal('hide');
                        });
                        break;
                }
        
                $('#ezAlerts').modal({ 
                show: false, 
                backdrop: backd, 
                keyboard: JSON.parse(keyb) 
                }).on('hidden.bs.modal', function (e) {
                    $('#ezAlerts').remove();
                    deferredObject.resolve(calbackParam);
                }).on('shown.bs.modal', function (e) {
                    if ($('#prompt').length > 0) {
                        $('#prompt').focus();
                    }
                }).modal('show');
            }
            _show();  
            return deferredObject.promise();    
        }


        var row;
        var initpos;
        function start(){
            row = event.target;
            let children= Array.from(event.target.parentNode.children);
            initpos = children.indexOf(event.target)
        }
        function dragover(){
            var e = event;
            e.preventDefault();
            if(row.tagName != e.target.parentNode.tagName) {
                return false
            }
            let children= Array.from(e.target.parentNode.parentNode.children);
            
            if(children.indexOf(e.target.parentNode)>children.indexOf(row))
                e.target.parentNode.after(row);
            else
                e.target.parentNode.before(row);
        }

        function moveArrayItemToNewIndex(arr, old_index, new_index) {
            if (new_index >= arr.length) {
                var k = new_index - arr.length + 1;
                while (k--) {
                    arr.push(undefined);
                }
            }
            arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
            return arr; 
        };

        function dragend() {
            var e = event;
            e.preventDefault()
            let children= Array.from(e.target.parentNode.children);
            var position = children.indexOf(e.target)
            if(position!=-1) {
                if(initpos!=position) {
                    var eleId = e.target.getAttribute('id')
                    var template = getFromStorage()
                    var currentIndex = template.layers.findIndex(ly=>ly.id==eleId)
                    template.layers = moveArrayItemToNewIndex(template.layers, currentIndex, position)
                    saveToStorage('template', template)
                    changeZIndexOfEle(template.layers)
                }
            }
        }

        function changeZIndexOfEle(layers) {
            var zIndex = layers.length
            layers.map(ly=>{
                if(ly.type=="image") {
                    $(`.layer-image-${ly.id}`).css('z-index', zIndex)
                } else {
                    $(`.layer-text-${ly.id}`).css('z-index', zIndex)
                }
                zIndex -= 1
            })
        }
        
        const INITIAL_WIDTH = 500
        const INITIAL_HEIGHT = 300
        const INITIAL_COLOR = '#563d7c'
        const LAYER_IMAGE_WIDTH = 70
        let LAYER = 1

        function saveToStorage(key, value) {
            key = "edit_template"
            localStorage.setItem(key, JSON.stringify(value))
        }

        function getFromStorage(key="template") {
            key = "edit_template"
            if(localStorage.getItem(key)) {
                return JSON.parse(localStorage.getItem(key))
            } else {
                return null
            }
        }

        function removeFromStorage(key) {
            localStorage.removeItem(key)
        }
        
        function initializeLayout(width, height) {
            // var container_width = $('.template-container').width()
            // var adjusted_width_ratio = width/INITIAL_WIDTH
            // var adjusted_width = INITIAL_WIDTH
            // if(width<INITIAL_WIDTH) {
            //     adjusted_width = width
            // }
            // var adjusted_height = adjusted_width_ratio*height
            $('.template-container').width(width+'px')
            $('.template-container').height(height+'px')
            $('.layout-width').val(width)
            $('.layout-height').val(height)
        }

        function parseLayout() {

            var template = {!! json_encode($template->toArray()) !!}


            $('input[name="template_name"]').val(template.template_name)

            initializeLayout(template.layout_width, template.layout_height)
            var theme = {
                type: template.theme_type,
                value: template.theme_value
            }
            parseTheme(theme)
            if(template.layers && template.layers.length>0) {
                template.layers.forEach(ly=>{
                    ly.position = JSON.parse(ly.position)
                    ly.style = JSON.parse(ly.style)
                })
                parseLayers(template.layers)
            }

            custom_template = {
                layout: {
                    width: template.layout_width,
                    height: template.layout_height
                },
                theme: {
                    type: template.theme_type,
                    value: template.theme_value
                }
            }
            saveToStorage('template', custom_template)
        }
        
        function saveLayout(width, height, template) {
            if(!template) {
                template = {}
            }
            template.layout = {
                width,
                height
            }
            saveToStorage('template', template)
        }

       $(document).ready(function() {
            // Initialize
            parseLayout()
        })


        function changeLayoutWidthHeight() {
            var {width, height} = getLayoutWidthFromInput()
            if(!width || !height) {
                alert('Please enter width and height')
            }
            initializeLayout(width, height)
            local_template = getFromStorage()
            saveLayout(width, height, local_template)
        }

        // Layout
        $('.layout-submit').on('click', function() {
            changeLayoutWidthHeight()
        })
        $('.layout-width').on('change', function() {
            changeLayoutWidthHeight()
        })
        $('.layout-height').on('change', function() {
            changeLayoutWidthHeight()
        })


        

        function getLayoutWidthFromInput() {
            var width = $('.layout-width').val()
            var height = $('.layout-height').val()
            return {
                width: Number(width),
                height: Number(height)
            } 
        }

        function saveTheme(type, value) {
            var template = getFromStorage()
            if(!template) {
                template = {}
            }
            template.theme = {
                type,
                value
            }
            saveToStorage('template', template)
        }

        function parseTheme(theme) {
            if(theme.type=="image") {
                $('.template-container').css('background', 'url("' + theme.value + '")');
            } else {
                $('.template-container').css('background', theme.value)
                $('.layout-bg-solid').val(theme.value)
            }
        }
        

        $('.layout-bg-image').on('change', function(e) {
            var img = e.target.files[0]
            var reader = new FileReader();
            var cropper = null
            const cropModal = new bootstrap.Modal(document.querySelector('#cropModal'))
            document.querySelector('#cropModal').addEventListener('hidden.bs.modal', event => {
                if(cropper) {
                    var b64img = cropper.getCroppedCanvas().toDataURL("image/png")
                    $('.template-container').css('background-image', 'url("' + b64img + '")');
                    saveTheme('image', b64img)
                    cropper.destroy()
                    cropper = null
                }
            })

            reader.onloadend = function () {
                $('.template-container').css('background-image', 'url("' + reader.result + '")');
                $('.bg-cropper').attr('src', reader.result)
                cropModal.show()
                const image = document.querySelector('.bg-cropper');
                var layout = getLayoutWidthFromInput()
                cropper = new Cropper(image, {
                    aspectRatio: (layout.width/layout.height)/1,
                    minContainerWidth: INITIAL_WIDTH,
                    minContainerHeight: INITIAL_HEIGHT,
                    movable: false,
                    autoCrop: false
                });
                saveTheme('image', reader.result)
            }
            if (img) {
                reader.readAsDataURL(img);
            }
            $('.crop-modal-btn').on('click', function(e) {
                if(cropper) {
                    var b64img = cropper.getCroppedCanvas().toDataURL("image/png")
                    $('.template-container').css('background-image', 'url("' + b64img + '")');
                    saveTheme('image', b64img)
                    cropper.destroy()
                    cropper = null
                }
                if(cropModal) {
                    cropModal.hide()
                }
            })
        })

        $('.layout-bg-reset').on('click', function(e) {
            $('.template-container').css('background', INITIAL_COLOR)
            $('.layout-bg-image').val('')
            $('.layout-bg-solid').val(INITIAL_COLOR)
            saveTheme('color', INITIAL_COLOR)
        })
        
        $('.layout-bg-solid').on('change', function(e) {
            $('.template-container').css('background', e.target.value)
            $('.layout-bg-image').val('')
            saveTheme('color', e.target.value)
        })
        
        $('legend').on('click', function(e) {
            var filedset = $(this).parent()
            if($(filedset).hasClass('close')) {
                $(filedset).removeClass('close')
            } else {
                $(filedset).addClass('close')
            }
        })

        

        function parseLayers(layers) {
            let rgb2hex=c=>'#'+c.match(/\d+/g).map(x=>(+x).toString(16).padStart(2,0)).join``
            var zIndex = layers.length
            layers.map(ly=>{
                if(ly.type=="image") {
                    appendImageLayer(ly.id, ly.width)
                    addImageToTemplate(ly.id, ly.value, ly.width, ly.position)
                    addLayerBox(ly.id)
                    changeImageInTemplate(ly.id, ly.width)
                    $(`.layer-image-${ly.id}`).css('z-index', zIndex)
                } else {
                    appendTextLayer(ly.id, ly.value, rgb2hex(ly.style.color), ly.style.fontSize, ly.style.fontWeight, ly.style.fontStyle, ly.style.textDecoration, ly.style.fontFamily)
                    addTextToTemplate(ly.id, ly.value, ly.position, ly.style)
                    addLayerBox(ly.id)
                    changeTextInTemplate(ly.id)
                    $(`.layer-text-${ly.id}`).css('z-index', zIndex)
                }
                zIndex -= 1
            })
            if(layers.length>0) {
                let highestId = Math.max.apply(null, layers.map(e => e.id));
                LAYER = highestId+1
            }
        }

        

        function appendImageLayer(id, width) {
            var layout = `<div class="offcanvas offcanvas-end" tabindex="-1" data-bs-scroll="true" id="offcanvasRight${id}" aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasRightLabel">Layer ${id}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <div class="form-group mb-3">
                                    <label class="form-label">Choose Image</label>
                                    <input type="file" class="form-control layer-image-${id}" data-id="${id}" accept="image/x-png,image/gif,image/jpeg" />
                                </div>
                                <div class="form-group mb-2">
                                    <label class="form-label">Resize Image</label>
                                    <input id="image-resize-${id}" class="form-range" min="10" max="${INITIAL_WIDTH}" value="${width}" type="range">
                                </div>
                                <button class="btn btn-primary add-to-layer-btn-${id}">Apply Layer</button>
                            </div>
                        </div>`
            $('.all-canvas').append(layout)
        }

        function changeElementPosition(id, x, y) {
            var template = getFromStorage()
            if(template && template.layers && template.layers.length>0) {
                var i = template.layers.findIndex(l=>l.id==id)
                if(i!=-1) {
                    template.layers[i].position.x = x
                    template.layers[i].position.y = y
                    saveToStorage('template', template)
                }
            }
        }

        function changeElementValue(id, value) {
            var template = getFromStorage()
            if(template && template.layers && template.layers.length>0) {
                var i = template.layers.findIndex(l=>l.id==id)
                if(i!=-1) {
                    template.layers[i].value = value
                    saveToStorage('template', template)
                }
            }
        }

        function changeElementWidth(id, width) {
            var template = getFromStorage()
            if(template && template.layers && template.layers.length>0) {
                var i = template.layers.findIndex(l=>l.id==id)
                if(i!=-1) {
                    template.layers[i].width = width
                    saveToStorage('template', template)
                }
            }
        }

        function changeElementStyle(id, style) {
            var template = getFromStorage()
            if(template && template.layers && template.layers.length>0) {
                var i = template.layers.findIndex(l=>l.id==id)
                if(i!=-1) {
                    template.layers[i].style = style
                    saveToStorage('template', template)
                }
            }
        }

        function getStyleFromLayer(id) {
            var template = getFromStorage()
            let rgb2hex=c=>'#'+c.match(/\d+/g).map(x=>(+x).toString(16).padStart(2,0)).join``
            if(template && template.layers && template.layers.length>0) {
                var i = template.layers.find(l=>l.id==id)
                if(i) {
                    i.style.color = rgb2hex(i.style.color)
                    return i.style
                }
            }
            return null
        }

        function getPositionFromLayer(id) {
            var template = getFromStorage()
            if(template && template.layers && template.layers.length>0) {
                var i = template.layers.find(l=>l.id==id)
                if(i) {
                    return i.position
                }
            }
            return null
        }

        function revertTextToOriginal(id) {
            $(`.layer-text-${id}`).remove()
            var template = getFromStorage()
            if(template && template.layers && template.layers.length>0) {
                var layer = template.layers.find(l=>l.id==id)
                if(layer) {
                    addTextToTemplate(layer.id, layer.value, layer.position, layer.style)
                }
            }
        }

        function removeElementFromLayer(id) {
            var template = getFromStorage()
            if(template && template.layers && template.layers.length>0) {
                template.layers = template.layers.filter(l=>l.id!=id)
                saveToStorage('template', template)
            }
        }

        function getLayerById(id) {
            var template = getFromStorage()
            if( template.layers && template.layers.length>0) {
            } else {
                return null
            }
            var find = template.layers.find(ly=>ly.id==id)
            if(find) {
                return find
            }
            return null
        }

        function changeLayerName(id) {
            const myModal = new bootstrap.Modal('#changeLayerName', {
                keyboard: false
            })
            var template = getFromStorage()
            var idx = template.layers.findIndex(ly=>ly.id==id)
            if(idx!=-1) {
                myModal.show()
                if(template.layers[idx].name) {
                    $('.layer_name_change').val(template.layers[idx].name)
                } else {
                    $('.layer_name_change').val(`LAYER_${id}`)
                }
                $('.layer-name-change-btn').on('click', function(){
                    var new_value = $('.layer_name_change').val()
                    template.layers[idx].name = new_value
                    saveToStorage('template', template)
                    myModal.hide()
                    $(`.layer-name-value-${id}`).html(new_value)
                })
            }
        }

        function addLayerBox(id) {
            var layer = getLayerById(id)
            var layer_name = `LAYER_${id}`
            if(layer && layer.name) {
                layer_name = layer.name
            }
            let ele = `<tr id="${id}" draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend()" class="btn btn-outline-primary layer-btn-id-${id}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight${id}" title="Drag to move layer | Right click to rename the layer">  
                        <td class="layer-name-value-${id}">${layer_name}</td>
                        </tr>`
            $('.layers-area').append(ele)
            $(`.layer-btn-id-${id}`).bind("contextmenu",function(e){
                changeLayerName(id)
                return false;
            });
        }

        function saveLayer(id, type, value, position, width=0, style="") {
            var template = getFromStorage()
            if(!template) {
                template = {}
            }
            if(!template.layers) {
                template.layers = []
            }
            template.layers.push({
                id,
                type,
                value,
                position,
                width,
                style
            })
            saveToStorage('template', template)
        }


        $('.add-layer-image').on('click', function(e) {
            let apply = false
            let width = LAYER_IMAGE_WIDTH
            appendImageLayer(LAYER, LAYER_IMAGE_WIDTH)

            $(`#image-resize-${LAYER}`).on('change', function(e) {
                width = e.target.value
                $(`.layer-image-${LAYER} img`).width(width+'px')
            })

            const myOffcanvas = document.getElementById(`offcanvasRight${LAYER}`)
            let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
            bsOffcanvas.show()
            myOffcanvas.addEventListener('hidden.bs.offcanvas', event => {
                if(!apply) {
                    myOffcanvas.remove()
                    $(`.layer-image-${LAYER}`).remove()
                }
            })
            $(`.add-to-layer-btn-${LAYER}`).on('click', function(e) {
                apply = true
                addLayerBox(LAYER)
                $(`.add-to-layer-btn-${LAYER}`).unbind("click");
                changeImageInTemplate(LAYER, width)
                bsOffcanvas.hide()
                var img = $(`.layer-image-${LAYER} img`).attr('src')
                var position = {x:0,y:0}
                saveLayer(LAYER, 'image', img, position, width)
                LAYER += 1
            })

            $(`.layer-image-${LAYER}`).on('change', function(e) {
                var id = $(this).attr('data-id')
                let file = e.target.files[0]
                let reader = new FileReader()
                reader.onloadend = function () {
                    addImageToTemplate(id, reader.result, LAYER_IMAGE_WIDTH)
                }
                if (file) {
                    reader.readAsDataURL(file);
                }
            })

        })

        function addImageToTemplate(id, value, width, position={x:0,y:0}) {
            var image_layer_id = `layer-image-${id}`
            let layout = `<div class="${image_layer_id}" style="left:${position.x}px;top:${position.y}px">
                                <image src="${value}" style="width: ${width}px; height:100%; object-fit:cover"/>
                            </div>`
            $('.template-real').append(layout)
            $(`.layer-image-${id}`).draggable({
                containment: ".template-real",
                scroll: false,
                stop: function(e,ui) {
                    var x = ui.position.left
                    var y = ui.position.top
                    changeElementPosition(id, x, y)
                }
            })
        }

        function changeImageInTemplate(id, width=0) {
            var apply = false
            var new_width = false
            var reset_value = $(`.layer-image-${id} img`).attr('src')

            var remove_layer_btn = `<button class="btn btn-danger remove-layer-btn-${id}">Remove Layer</button>`
            $(`.add-to-layer-btn-${id}`).parent().append(remove_layer_btn)

            $(`.layer-image-${id}`).on('change', function(e) {
                let file = e.target.files[0]
                let reader = new FileReader()
                reader.onloadend = function () {
                    $(`.layer-image-${id} img`).attr('src',reader.result)
                }
                if (file) {
                    reader.readAsDataURL(file);
                }
            })
            $(`#image-resize-${id}`).on('change', function(e) {
                new_width = e.target.value
                $(`.layer-image-${id} img`).width(new_width+'px')
            })
            const myOffcanvas = document.getElementById(`offcanvasRight${id}`)
            let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
            myOffcanvas.addEventListener('hidden.bs.offcanvas', event => {
                if(!apply) {
                    $(`.layer-image-${id} img`).attr('src', reset_value)
                    $(`.layer-image-${id} img`).width(width+'px')
                }
            })

            $(`.add-to-layer-btn-${id}`).on('click', function(e) {
                var new_value = $(`.layer-image-${id} img`).attr('src')
                changeElementValue(id, new_value)
                if(new_width) {
                    changeElementWidth(id, new_width)
                }
                apply = true
                bsOffcanvas.hide()
            })
            $(`.remove-layer-btn-${id}`).on('click', function(e) {
                bsOffcanvas.hide()
                myOffcanvas.remove()
                $(`.layer-image-${id}`).remove()
                removeElementFromLayer(id)
                $(`.layer-btn-id-${id}`).remove()
            })
        }




        function addTextToTemplate(id, value, position={x:0,y:0}, style={}) {
            var ele = `<div class="default-template-text layer-text-${id}" style="left:${position.x}px;top:${position.y}px;color:${style.color};font-size:${style.fontSize}rem;font-weight:${style.fontWeight};font-style:${style.fontStyle};text-decoration:${style.textDecoration};font-family:${style.fontFamily}">${value}</div>`
            $('.template-real').append(ele)
            $(`.layer-text-${id}`).draggable({
                containment: ".template-real",
                scroll: false,
                stop: function(e,ui) {
                    var x = ui.position.left
                    var y = ui.position.top
                    changeElementPosition(id, x, y)
                }
            })
        }

        function parseFonts(fontFamily="inherit") {
            var option = `<option value="">Select Fonts</option>`
            for (const font of fontCheck) {
                option += `<option value="${font}" style="font-family: ${font}" ${fontFamily==font?'selected':''}>${font}</option>`
            }
            return option
        }

        function appendTextLayer(id, value="", color="#ffffff", fontSize=1, fontWeight="normal", fontStyle="normal", textDecoration="none", fontFamily="inherit") {
            var position = {x:0,y:0}
            var l = getStyleFromLayer(id)
            if(l) {
                var {color, fontSize, fontWeight, fontStyle, textDecoration, fontFamily} = l
            }
            var p = getPositionFromLayer(id)
            if(p) {
                position = p
            }
            var layout = `<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight${id}" aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasRightLabel">Layer ${id}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <div class="form-group mb-3">
                                    <label class="form-label">Text</label>
                                    <input type="text" class="form-control" id="layer-text-${id}" value="${value}" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Color</label>
                                    <input type="color" class="form-control form-control-color" value="${color}" id="layer-text-color-${id}" />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="fontFamilySelect" class="form-label">Font Family</label>
                                    <select class="form-select" id="font-family-select-${id}">
                                        ${parseFonts(fontFamily)}
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Font Size</label>
                                    <div class="d-flex align-items-center text-center form-control">
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm font-minus-${id}">-</button>
                                        </div>
                                        <div class="col font-text-${id}">${fontSize}</div>
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm font-plus-${id}">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Font Weight</label>
                                    <div class="row">
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-font-weight btn-font-weight-${id}" data-id="normal" ${fontWeight=="normal"?'disabled':''}>Normal</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-font-weight btn-font-weight-${id}" data-id="500" style="font-weight: 500" ${fontWeight=="500"?'disabled':''}>Bold</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-font-weight btn-font-weight-${id}" data-id="900" style="font-weight: 900" ${fontWeight=="900"?'disabled':''}>Bolder</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Font Style</label>
                                    <div class="row">
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-font-style btn-font-style-${id}" data-id="normal" ${fontStyle=="normal"?'disabled':''}>Normal</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-font-style btn-font-style-${id}" data-id="italic" style="font-style: italic" ${fontStyle=="italic"?'disabled':''}>Italic</button>
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Text Decoration</label>
                                    <div class="row">
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-text-decoration btn-text-decoration-${id}" data-id="none" ${textDecoration=="none"?'disabled':''}>None</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-text-decoration btn-text-decoration-${id}" data-id="line-through" style="text-decoration: line-through" ${textDecoration.includes("line-through")?'disabled':''}>Line Through</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-text-decoration btn-text-decoration-${id}" data-id="overline" style="text-decoration: overline" ${textDecoration.includes("overline")?'disabled':''}>Overline</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-dark btn-sm btn-text-decoration btn-text-decoration-${id}" data-id="underline" style="text-decoration: underline" ${textDecoration.includes("underline")?'disabled':''}>Underline</button>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary add-to-layer-btn-${id}">Apply Layer</button>
                            </div>
                        </div>`
            $('.all-canvas').append(layout)
            $(`#layer-text-${id}`).on('keyup', function(e) {
                $(`.layer-text-${id}`).remove()
                var style = {
                    color,
                    fontSize,
                    fontWeight,
                    fontStyle,
                    textDecoration,
                    fontFamily
                }
                addTextToTemplate(id, e.target.value, position, style)
            })
            $(`#font-family-select-${id}`).on('change', function(e) {
                if(!e.target.value) return
                fontFamily = e.target.value
                $(`.layer-text-${id}`).css('font-family', fontFamily)
            })
            $(`#layer-text-color-${id}`).on('change', function(e) {
                $(`.layer-text-${id}`).css('color', e.target.value)
            })
            $(`.font-plus-${id}`).on('click', function(e) {
                fontSize = Number(fontSize)
                fontSize += 0.1
                fontSize = fontSize.toFixed(1)
                $(`.layer-text-${id}`).css('font-size', fontSize+"rem")
                $(`.font-text-${id}`).html(fontSize)
            })
            $(`.font-minus-${id}`).on('click', function(e) {
                fontSize = Number(fontSize)
                fontSize -= 0.1
                fontSize = fontSize.toFixed(1)
                $(`.layer-text-${id}`).css('font-size', fontSize+"rem")
                $(`.font-text-${id}`).html(fontSize)
            })
            $(`.btn-font-weight-${id}`).on('click', function(e) {
                $('.btn-font-weight').attr('disabled', false)
                $(this).attr('disabled', true)
                var value = $(this).attr('data-id')
                fontWeight = value
                $(`.layer-text-${id}`).css('font-weight', value)
            })
            $(`.btn-font-style-${id}`).on('click', function(e) {
                $('.btn-font-style').attr('disabled', false)
                $(this).attr('disabled', true)
                var value = $(this).attr('data-id')
                fontStye = value
                $(`.layer-text-${id}`).css('font-style', value)
            })
            $(`.btn-text-decoration-${id}`).on('click', function(e) {
                $('.btn-text-decoration').attr('disabled', false)
                $(this).attr('disabled', true)
                var value = $(this).attr('data-id')
                textDecoration = value
                $(`.layer-text-${id}`).css('text-decoration', value)
            })
        }



        $('.add-layer-text').on('click', function(e) {
            let apply = false
            
            appendTextLayer(LAYER)
            const myOffcanvas = document.getElementById(`offcanvasRight${LAYER}`)
            let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
            bsOffcanvas.show()
            myOffcanvas.addEventListener('hidden.bs.offcanvas', event => {
                if(!apply) {
                    myOffcanvas.remove()
                    $(`.layer-text-${LAYER}`).remove()
                }
            })

            $(`.add-to-layer-btn-${LAYER}`).on('click', function(e) {
                apply = true
                addLayerBox(LAYER)
                $(`.add-to-layer-btn-${LAYER}`).unbind("click");
                changeTextInTemplate(LAYER)
                var value = $(`.layer-text-${LAYER}`).text()
                var position = {x:0,y:0}
                var style = {
                    color: $(`.layer-text-${LAYER}`).css('color'),
                    fontSize:$(`.layer-text-${LAYER}`).css('font-size'),
                    fontWeight:$(`.layer-text-${LAYER}`).css('font-weight'),
                    fontStyle:$(`.layer-text-${LAYER}`).css('font-style'),
                    textDecoration:$(`.layer-text-${LAYER}`).css('text-decoration'),
                    fontFamily: $(`.layer-text-${LAYER}`).css('font-family')
                }
                style.fontFamily = style.fontFamily.replace(/^"(.*)"$/, '$1');
                if(style.fontSize.includes('px')) {
                    var fs = style.fontSize.replace('px', '')
                    fs = Number(fs)/16
                    style.fontSize = fs
                }
                saveLayer(LAYER, "text", value, position, 0, style)
                bsOffcanvas.hide()
                LAYER += 1
            })
        })

        function changeTextInTemplate(id) {
            let apply = false
            let ini_style
            let ini_value

            var remove_layer_btn = `<button class="btn btn-danger remove-layer-btn-${id}">Remove Layer</button>`
            $(`.add-to-layer-btn-${id}`).parent().append(remove_layer_btn)

            const myOffcanvas = document.getElementById(`offcanvasRight${id}`)
            let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)


            myOffcanvas.addEventListener('hidden.bs.offcanvas', event => {
                if(!apply) {
                    //revert
                    revertTextToOriginal(id)
                }
            })

            $(`.add-to-layer-btn-${id}`).on('click', function(e) {
                value = $(`.layer-text-${id}`).text()
                style = {
                    color: $(`.layer-text-${id}`).css('color'),
                    fontSize:$(`.layer-text-${id}`).css('font-size'),
                    fontWeight:$(`.layer-text-${id}`).css('font-weight'),
                    fontStyle:$(`.layer-text-${id}`).css('font-style'),
                    textDecoration:$(`.layer-text-${id}`).css('text-decoration'),
                    fontFamily:$(`.layer-text-${id}`).css('font-family')
                }
                style.fontFamily = style.fontFamily.replace(/^"(.*)"$/, '$1');
                if(style.fontSize.includes('px')) {
                    var fs = style.fontSize.replace('px', '')
                    fs = Number(fs)/16
                    style.fontSize = fs
                }
                changeElementValue(id, value)
                changeElementStyle(id, style)
                apply = true
                bsOffcanvas.hide()
            })

            $(`.remove-layer-btn-${id}`).on('click', function(e) {
                bsOffcanvas.hide()
                myOffcanvas.remove()
                $(`.layer-text-${id}`).remove()
                removeElementFromLayer(id)
                $(`.layer-btn-id-${id}`).remove()
            })

        }

        function appendToForm(form, name, value) {
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = name;
            input.value = value;
            form.appendChild(input);
        }

        async function screenshotTemplate(className) {
            var canvas = await html2canvas(document.querySelector(`.${className}`))
            var dataUrl = canvas.toDataURL()
            return dataUrl
        }

        let template_submit = false

        $('.submit-template-form').on('submit', async function(e) {
            if(!template_submit) {
                e.preventDefault()
            }
            if(confirm("Are you sure you want to submit this template?")) {
                $('#pageLoader').show();
                template_submit = true
                const form = document.querySelector('.submit-template-form')
                $('.submit-template-btn').attr('disabled', true) 
                var cover = await screenshotTemplate('template-container')
                appendToForm(form, 'template_cover', cover)

                var fields = getFromStorage()
                if(fields.layout) {
                    appendToForm(form, 'layout_width', fields.layout.width)
                    appendToForm(form, 'layout_height', fields.layout.height)
                }
                if(fields.theme) {
                    appendToForm(form, 'theme_type', fields.theme.type)
                    appendToForm(form, 'theme_value', fields.theme.value)
                }
                if(fields.layers) {
                    appendToForm(form, 'layers', JSON.stringify(fields.layers))
                }
                form.submit()
            }
        })

     


       

    </script>

@endsection