export interface CategoryImageOptions {
    /**
     * Real Config
     */
    version: string | null;
    placeholder: string | null;

    /**
     * Element Selectors
     */
    uploadImageButtonSelector: string;
    removeImageButtonSelector: string;
    editInlineSelector: string;
    taxonomyImageSelector: string;
    imageUrlInputSelector: string;
    attachmentIdInputSelector: string;
    categoryImageElSelector: string;
    inlineEditColImageEl: string;
    inlineEditColInputSelector: string;
    inlineEditColAttachmentIdInputSelector: string;
    taxonomyListingAttachmentIdInputSelector: string;
    editCategoryListSelector: string;
}