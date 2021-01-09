import {extendDefaults} from "../../js/helper/extend-defaults.helper";

export default class CategoryMenu {
    private el: HTMLElement;
    private options: object;

    constructor(el, options)
    {
        const _defaults = {};

        this.el = el || el;
        this.options = extendDefaults(_defaults, options);

        this.init();
    }

    public init(): void
    {
        this.registerEvents();
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