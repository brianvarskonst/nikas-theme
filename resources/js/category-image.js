jQuery(document).ready(function($) {
    var upload_button;

    $(".z_upload_image_button").on('click', function(event) {
        upload_button = $(this);

        var frame;

        if (nikasCategoryImage.version >= "3.5") {
            event.preventDefault();

            if (frame) {
                frame.open();
                return;
            }

            frame = wp.media();

            frame.on("select", function() {
                // Grab the selected attachment.
                var attachment = frame.state().get("selection").first();
                var attachmentUrl = attachment.attributes.url;

                // attachmentUrl = attachmentUrl.replace('-scaled.', '.');

                frame.close();

                $(".zci-taxonomy-image").attr("src", attachmentUrl);

                if (upload_button.parent().prev().children().hasClass("tax_list")) {
                    upload_button.parent().prev().children().val(attachmentUrl);
                    upload_button.parent().prev().prev().children().attr("src", attachmentUrl);
                } else {
                    var categoryImageInput = document.querySelector('input#category-image');

                    categoryImageInput.defaultValue  = attachmentUrl;
                    categoryImageInput.innerHTML = attachmentUrl;
                    // $("#category-image").val(attachmentUrl);
                }
            });

            frame.open();
        }
        else {
            tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
            return false;
        }
    });

    $(".z_remove_image_button").on('click', function() {
        $(".zci-taxonomy-image").attr("src", nikasCategoryImage.placeholder);
        $("#category-image").val("");

        $(this).parent().siblings(".title").children("img").attr("src", nikasCategoryImage.placeholder);

        $(".inline-edit-col :input[name='category-image']").val("");

        return false;
    });

    if (nikasCategoryImage.version < "3.5") {
        window.send_to_editor = function(html) {
            imgurl = $("img",html).attr("src");

            if (upload_button.parent().prev().children().hasClass("tax_list")) {
                upload_button.parent().prev().children().val(imgurl);
                upload_button.parent().prev().prev().children().attr("src", imgurl);
            } else {
                $("#zci_taxonomy_image").val(imgurl);
            }
            tb_remove();
        }
    }

    $(".editinline").on('click', function() {
        var tax_id = $(this).parents("tr").attr("id").substr(4);
        var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");

        // To Do: fix image input url in quick mode
        /*if (thumb != nikasCategoryImage.placeholder) {
            $(".inline-edit-col :input[name='zci_taxonomy_image']").val(thumb);
        } else {
            $(".inline-edit-col :input[name='zci_taxonomy_image']").val("");
        }*/

        $(".inline-edit-col .title img").attr("src",thumb);
    });
});