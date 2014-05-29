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
            height: 300,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons paste textcolor responsivefilemanager"
            ],
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
            image_advtab: true,
            external_filemanager_path: "/filemanager/",
            filemanager_title: "Responsive Filemanager",
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
            doModal();
        });
    });

    function doModal() {
        $("a[data-modal='gallerymodal']").on('click', function() {
            $('#galleryModal').load($(this).data('remote'));
            $('#galleryModal').modal();
        });
    }

    $(document).on('hidden.bs.modal',  '.modal', function(e) {
        
        tinymce.remove('#galleryentry');
    });
    
    jQuery('#myModal').on("shown",function(){
        initTiny();
    });
    
    
    $(document).on('shown.bs.modal',  '.modal', function(e) {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
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
                    entry: tinymce.get('galleryentry').getContent()
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