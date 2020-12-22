import {extendDefaults} from "../helper/extend-defaults.helper";

export default class CategoryImage {
    constructor(config) {
        this._defaults = {
            version: null,
            placeholder: null,
        }

        this.options = extendDefaults(this._defaults, config);

        this.init();
    }

    init()
    {
        this.uploadImageButton = document.querySelector('.z_upload_image_button') || null;
        this.removeImageButton = document.querySelector('.z_remove_image_button') || null;
        this.editInline = document.querySelector('.editinline') || null;

        this.registerEvents();
    }

    registerEvents()
    {
        if (this.uploadImageButton !== null) {
            this.uploadImageButton.addEventListener('click', this.onClickUploadImageButton.bind(this));
        }

        if (this.removeImageButton !== null) {
            this.removeImageButton.addEventListener('click', this.onClickRemoveImageButton.bind(this));
        }

        if (this.editInline !== null) {
            this.editInline.addEventListener('click', this.onCLickEditInline.bind(this));
        }
    }

    onClickUploadImageButton(e)
    {
        let frame;

        e.preventDefault();

        if (frame) {
            frame.open();

            return;
        }

        frame = wp.media();

        frame.on("select", () => {
            // Grab the selected attachment.
            const attachment = frame.state().get('selection').first();

            const attachmentUrl = attachment.attributes.url;
            const attachmentId = attachment.attributes.id;

            frame.close();

            const imageElement = document.querySelector('.zci-taxonomy-image');

            imageElement.src = attachmentUrl;

            // QuickEdit mode 'tax_list'
            // if (this.uploadImageButton.parentElement.previousElementSibling.children[0].classList.contains("tax_list")) {
            //     this.uploadImageButton.parentElement.previousElementSibling.children[0].val = attachmentUrl;
            //     this.uploadImageButton.parentElement.previousElementSibling.previousElementSibling.children[0].src = attachmentUrl;
            // }

            // Regular Mode
            let categoryImageInput = document.querySelector('input#category-image');

            categoryImageInput.defaultValue = attachmentUrl;
            categoryImageInput.innerHTML = attachmentUrl;

            const hiddenInputAttachmentId = document.querySelector('input[name="category-image-attachment-id"]');
            hiddenInputAttachmentId.defaultValue = attachmentId;
        });

        frame.open();
    }

    onClickRemoveImageButton()
    {
        const imageEl = document.querySelector('.zci-taxonomy-image');
        imageEl.src = this.options.placeholder;

        const categoryImageInput = document.querySelector('#category-image');
        categoryImageInput.value = '';

        this.removeImageButton.parent().siblings(".title").children("img").attr("src", this.options.placeholder);

        const inlineEditColInputEl = document.querySelector('.inline-edit-col :input[name="category-image"]');
        inlineEditColInputEl.value = '';

        return false;
    }

    onCLickEditInline()
    {
        const taxId = this.editInline.parents('tr').attr('id').substr(4)
        const thumb = document.querySelector(`#tag-${taxId} .thumb img`);

        const inlineEditColEl = document.querySelector('.inline-edit-col .title img');

        inlineEditColEl.src = thumb.src;

        // To Do: fix image input url in quick mode
        /*if (thumb != nikasCategoryImage.placeholder) {
            $(".inline-edit-col :input[name='zci_taxonomy_image']").val(thumb);
        } else {
            $(".inline-edit-col :input[name='zci_taxonomy_image']").val("");
        }*/
    }
}