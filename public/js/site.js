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
        selector: "textarea", theme: "modern", height: 300,
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
        external_plugins: {"filemanager": "/filemanager/plugin.min.js"}
    });

    function getTree() {
        $.ajax({
            url: "/json/galleries",
            dataType: 'json'
        }).done(function( data ){
            $('#tree').treeview({
                data: data,
                levels: 1,
                selectLeafOnly: true,
            });
        });
    }
    getTree();
    
    $('#tree').on('nodeSelected', function(event, node) {
        console.log(node);
        $.get( "/json/loadgallerie/tag/" + node.element, function( data ) {
            $( "#shootings" ).html( data );
            initGridster();
        });
    });
    
    function initGridster() {
        $(".gridster").gridster({
            widget_margins: [10, 10],
            widget_base_dimensions: [140, 140],
            widget_selector: 'div',
            max_cols: 3,
        });
    }
});

