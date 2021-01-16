import {extendDefaults} from "../helper/extend-defaults.helper";
import {parents} from "../helper/parents.helper";
import ElementObserver from "../components/ElementObserver";
import {CategoryImageOptions} from "./index";

export default class CategoryImage {
    private readonly options: CategoryImageOptions;

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
            inlineEditColInputSelector: '.inline-edit-col input[name="category-image"]',
            inlineEditColAttachmentIdInputSelector: '.inline-edit-col input[name="category-image-attachment-id"]',
            taxonomyListingAttachmentIdInputSelector: '.CategoryImageAttachmentIdTaxonomyListing',
            editCategoryListSelector: '#the-list'
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

        document.addEventListener('DOMContentLoaded', () => {
            const categoryListEl = document.querySelector(this.options.editCategoryListSelector);

            if (categoryListEl !== null) {
                const categoryListObserver = new ElementObserver(
                    categoryListEl,
                    {attributes: false, childList: true, characterData: false, subtree:true},
                    (mutations: MutationRecord[], observer: MutationObserver) => {
                        mutations.forEach((mutation: MutationRecord) => {
                            mutation.addedNodes.forEach((addedNode: Node) => {
                                // @ts-ignore
                                const uploadImageButton = addedNode.querySelector(this.options.uploadImageButtonSelector)

                                if (uploadImageButton !== null) {
                                    uploadImageButton.addEventListener(
                                        'click',
                                        // @ts-ignore
                                        (e) => this.onCLickInlineEditUploadiMageButton(e, addedNode)
                                    );

                                    return true;
                                }
                            })
                        });
                    }
                );
                categoryListObserver.observe();
            }
        });
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

        // @ts-ignore
        wpFileUploader = wp.media();

        wpFileUploader.on('select', () => {
            // Grab the selected attachment.
            const attachment = wpFileUploader.state().get('selection').first();

            const attachmentUrl = attachment.attributes.url;
            const attachmentId = attachment.attributes.id;

            wpFileUploader.close();

            const imageElement: HTMLImageElement = document.querySelector(this.options.taxonomyImageSelector);
            imageElement.src = attachmentUrl;

            // Regular Mode
            let categoryImageInput: HTMLInputElement = document.querySelector(this.options.imageUrlInputSelector);

            categoryImageInput.defaultValue = attachmentUrl;
            categoryImageInput.innerHTML = attachmentUrl;

            const hiddenInputAttachmentId: HTMLInputElement = document.querySelector(this.options.attachmentIdInputSelector);
            hiddenInputAttachmentId.defaultValue = attachmentId;
        });

        wpFileUploader.open();
    }

    public onCLickInlineEditUploadiMageButton(e: Event, element: Element): void
    {
        console.log(element);

        let wpFileUploader;

        e.preventDefault();

        if (wpFileUploader) {
            wpFileUploader.open();

            return;
        }

        // @ts-ignore
        wpFileUploader = wp.media();

        wpFileUploader.on('select', () => {
            // Grab the selected attachment.
            const attachment = wpFileUploader.state().get('selection').first();

            const attachmentUrl = attachment.attributes.url;
            const attachmentId = attachment.attributes.id;

            wpFileUploader.close();

            const imageElement: HTMLImageElement = document.querySelector(this.options.taxonomyImageSelector);
            imageElement.src = attachmentUrl;

            // Regular Mode
            let categoryImageInput: HTMLInputElement = document.querySelector(this.options.imageUrlInputSelector);

            categoryImageInput.defaultValue = attachmentUrl;
            categoryImageInput.innerHTML = attachmentUrl;

            const hiddenInputAttachmentId: HTMLInputElement = document.querySelector(this.options.attachmentIdInputSelector);
            hiddenInputAttachmentId.defaultValue = attachmentId;
        });

        wpFileUploader.open();
    }

    // TODO: To Fix
    public onClickRemoveImageButton(): void
    {
        const imageEl: HTMLImageElement = document.querySelector(this.options.taxonomyImageSelector);
        imageEl.src = this.options.placeholder;

        const categoryImageInput: HTMLInputElement = document.querySelector(this.options.categoryImageElSelector);
        categoryImageInput.value = '';

        // FIX!
        // this.removeImageButton.parent().siblings('.title').children('img').attr('src', this.options.placeholder);

        const inlineEditColInputEl: HTMLInputElement = document.querySelector(this.options.inlineEditColInputSelector);
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

        const taxonomyListingAttachmentIdEl: HTMLInputElement = taxEl.querySelector(this.options.taxonomyListingAttachmentIdInputSelector);

        if (taxonomyListingAttachmentIdEl.value !== null) {
            const inlineEditColAttachmentIdEl: HTMLInputElement = document.querySelector(this.options.inlineEditColAttachmentIdInputSelector);

            if (inlineEditColAttachmentIdEl !== null) {
                const attachmentId: string = taxonomyListingAttachmentIdEl.value;

                inlineEditColAttachmentIdEl.value = attachmentId;
                inlineEditColAttachmentIdEl.defaultValue = attachmentId;
            }
        }
    }
}