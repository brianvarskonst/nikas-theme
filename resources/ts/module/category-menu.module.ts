import merge from "ts-deepmerge"

export default class CategoryMenu {
    private el: HTMLElement;
    private options: object;

    constructor(el, options)
    {
        const defaults = {}

        this.el = el || null
        this.options = merge(defaults, options)

        this.init();
    }

    public init(): void
    {
        this.registerEvents()
    }

    public registerEvents(): void
    {
        document.addEventListener(
            "DOMContentLoaded",
            () => {
                this.onLoad();
            }
        );
    }

    public onLoad()
    {
    }
}