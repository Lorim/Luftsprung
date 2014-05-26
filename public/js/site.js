$(document).ready(function() {

    $("[title]").tooltip();

    $('.notifications').notify({
        fadeOut: {
            enabled: true,
            delay: 5000
        },
        type: 'bangTidy'
    }).show();

    tinymce.init({
        selector: "textarea",
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
            $('#tree').treeview('select', '3');
        });

    }
    getTree();

    $('#tree').on('nodeSelected', function(event, node) {
        $.get("/json/loadgallerie/tag/" + node.element, function(data) {
            $("#gallerytag").val(node.element);
            $("#shootings").html(data);
        });
    });

    $(document).on('hidden.bs.modal', function(e) {
        $(e.target).removeData('bs.modal');
    });

    $(document).on('shown.bs.modal', function(e) {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#updateGallery').on('click', function() {
            if ($('#delete').is(':checked')) {
                $.post("/admin/deletegallery/id/" + $('#galleryid').val());
            } else {
                $.post("/admin/addgallery/title/" + $('#galleriename').val() + "/created/" + $('#datum').val() + "/tag/" + $('#gallerytag').val())
                        .done(function(data) {

                        });
            }
            $.get("/json/loadgallerie/tag/" + $('#gallerytag').val(), function(data) {
                $("#shootings").html(data);
            });
            $('#galleryModal').modal('hide');
        });
    });

    $("#Galleryform").submit(function(e)
    {
        var formObj = $(this);
        var formURL = formObj.attr("action");
        var formData = new FormData(this);
        $.ajax({
            url: formURL,
            type: 'POST',
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function(data, textStatus, jqXHR)
            {

            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            }
        });
        e.preventDefault(); //Prevent Default action. 
        e.unbind();
    });
    $("#Galleryform").submit(); //Submit the form

});