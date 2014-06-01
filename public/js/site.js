$(document).ready(function() {

    $("[title]").tooltip();

    $('.notifications').notify({
        fadeOut: {
            enabled: true,
            delay: 5000
        },
        type: 'bangTidy'
    }).show();

    function initTiny() {
        tinymce.init({
            selector: "#galleryentry",
            theme: "modern",
            height: 500,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
            ],
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
            image_advtab: false,
            content_css: ["/css/site.css"],
            external_filemanager_path: "/filemanager/",
            filemanager_title: "Responsive Filemanager",
            relative_urls: false,
            external_plugins: {
                "filemanager": "/filemanager/plugin.min.js"
            }
        });
    }
    
    
    function getTree() {
        $.ajax({
            url: "/json/galleries",
            dataType: 'json'
        }).done(function(data) {
            $('#tree').treeview({
                data: data,
                levels: 1,
                selectLeafOnly: true,
            });
        });

    }
    getTree();

    $('#tree').on('nodeSelected', function(event, node) {
        $.get("/json/loadgallerie/tag/" + node.element, function(data) {
            $("#gallerytag").val(node.element);
            $("#shootings").html(data);
        }).done( function data() {
            doModal();
        });
    });

    function doModal() {
        $("a[data-modal='gallerymodal']").on('click', function() {
            $.get($(this).data('remote'), function(data) {
                $('#galleryModal').html(data);
            }).done( function data() {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd'
                });
                $('#galleryModal').modal();
            });
        });
    }

    $(document).on('hidden.bs.modal',  '#galleryModal', function(e) {
        tinymce.remove('#galleryentry');
    });
    
    jQuery('#myModal').on("shown",function(){
        initTiny();
    });
    
    
    $(document).on('shown.bs.modal', '#galleryModal', function(e) {
        // initialize TinyMCE Editor
        initTiny();
        $('#updateGallery').on('click', function() {
            if ($('#delete').is(':checked')) {
                $.post("/admin/deletegallery/id/" + $('#galleryid').val());
            } else {
                $.post( "/admin/addgallery", { 
                    title: $('#galleriename').val(),
                    created: $('#datum').val(),
                    tag: $('#gallerytag').val(),
                    galleryid: $('#galleryid').val(),
                    entry: tinymce.get('galleryentry').getContent(),
                    preview: $('#preview').val(),
                    active: $('#active').is(':checked'),
                });
            }
            $.get("/json/loadgallerie/tag/" + $('#gallerytag').val(), function(data) {
                $("#shootings").html(data);
                doModal();
            });
            $('#galleryModal').modal('hide');
        });
    });


});