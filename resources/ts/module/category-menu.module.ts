import {extendDefaults} from "../helper/extend-defaults.helper";

export default class CategoryMenu {
    private el: HTMLElement;
    private options: object;

    constructor(el, options)
    {
        const defaults = {};

        this.el = el || el;
        // @ts-ignore
        this.options = extendDefaults(defaults, options);

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