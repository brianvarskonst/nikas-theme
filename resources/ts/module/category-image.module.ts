import {extendDefaults} from "../helper/extend-defaults.helper";
import {parents} from "../helper/parents.helper";;

export default class CategoryImage {
    private readonly options: object;

    private uploadImageButton?: Element;
    private removeImageButton?: Element;
    private editInline?: Element;

    constructor(config: object) {
        const defaults = {
            version: null,
            placeholder: null,

            uploadImageButtonSelector: '.z_upload_image_button',
            removeImageButtonSelector: '.z_remove_image_button',
            editInlineSelector: '.editinline',
            taxonomyImageSelector: '.zci-taxonomy-image',
            imageUrlInputSelector: 'input#category-image',
            attachmentIdInputSelector: 'input[name="category-image-attachment-id"]',
            categoryImageElSelector: '#category-image',
            inlineEditColImageEl: '.inline-edit-col .thumbnail-image img',
            inlineEditColInputSelector: '.inline-edit-col input[name="category-image"]'
        }

        // @ts-ignore
        this.options = extendDefaults(defaults, config);

        this.init();
    }

    public init(): void
    {
        this.uploadImageButton = document.querySelector(this.options.uploadImageButtonSelector) || null;
        this.removeImageButton = document.querySelector(this.options.removeImageButtonSelector) || null;
        this.editInline = document.querySelector(this.options.editInlineSelector) || null;

        this.registerEvents();
    }

    public registerEvents(): void
    {
        if (this.uploadImageButton !== null) {
            this.uploadImageButton.addEventListener(
                'click',
                this.onClickUploadImageButton.bind(this)
            );
        }

        if (this.removeImageButton !== null) {
            this.removeImageButton.addEventListener(
                'click',
                this.onClickRemoveImageButton.bind(this)
            );
        }

        if (this.editInline !== null) {
            this.editInline.addEventListener(
                'click',
                this.onCLickEditInline.bind(this)
            );
        }
    }

    /**
     * @param {Event} e
     */
    public onClickUploadImageButton(e: Event): void
    {
        let wpFileUploader;

        e.preventDefault();

        if (wpFileUploader) {
            wpFileUploader.open();

            return;
        }

        wpFileUploader = wp.media();

        wpFileUploader.on('select', () => {
            // Grab the selected attachment.
            const attachment = wpFileUploader.state().get('selection').first();

            const attachmentUrl = attachment.attributes.url;
            const attachmentId = attachment.attributes.id;

            wpFileUploader.close();

            const imageElement = document.querySelector(this.options.taxonomyImageSelector);

            imageElement.src = attachmentUrl;

            // QuickEdit mode 'tax_list'
            // if (this.uploadImageButton.parentElement.previousElementSibling.children[0].classList.contains("tax_list")) {
            //     this.uploadImageButton.parentElement.previousElementSibling.children[0].val = attachmentUrl;
            //     this.uploadImageButton.parentElement.previousElementSibling.previousElementSibling.children[0].src = attachmentUrl;
            // }

            // Regular Mode
            let categoryImageInput = document.querySelector(this.options.imageUrlInputSelector);

            categoryImageInput.defaultValue = attachmentUrl;
            categoryImageInput.innerHTML = attachmentUrl;

            const hiddenInputAttachmentId: HTMLInputElement = document.querySelector(this.options.attachmentIdInputSelector);
            hiddenInputAttachmentId.defaultValue = attachmentId;
        });

        wpFileUploader.open();
    }

    public onClickRemoveImageButton(): void
    {
        const imageEl: HTMLImageElement = document.querySelector(this.options.taxonomyImageSelector);
        imageEl.src = this.options.placeholder;

        const categoryImageInput: HTMLInputElement = document.querySelector(this.options.categoryImageElSelector);
        categoryImageInput.value = '';

        this.removeImageButton.parent().siblings('.title').children('img').attr('src', this.options.placeholder);

        const inlineEditColInputEl = document.querySelector(this.options.inlineEditColInputSelector);
        inlineEditColInputEl.value = '';
    }

    public onCLickEditInline(): void
    {
        const taxEl: Element = parents(this.editInline.parentNode, 'tr');

        if (taxEl === null) {
            return;
        }

        const taxId = taxEl.id.substr(4)
        const thumb: HTMLImageElement = document.querySelector(`#tag-${taxId} .image img`);

        const inlineEditColImageEl: HTMLImageElement = document.querySelector(this.options.inlineEditColImageEl);

        inlineEditColImageEl.src = thumb.src;

        const inlineEditColInputEl: HTMLInputElement = document.querySelector(this.options.inlineEditColInputSelector);
        inlineEditColInputEl.defaultValue = thumb.src;

        // To Do: fix image input url in quick mode
        /*if (thumb != nikasCategoryImage.placeholder) {
            $(".inline-edit-col :input[name='zci_taxonomy_image']").val(thumb);
        } else {
            $(".inline-edit-col :input[name='zci_taxonomy_image']").val("");
        }*/
    }
}