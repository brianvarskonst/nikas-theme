import merge from "ts-deepmerge"
import { tns, TinySliderInstance } from "tiny-slider/src/tiny-slider"

export default class CategoryMenu {
    private el: HTMLElement;
    private options: object;

    private slider?: TinySliderInstance = null

    constructor(el, options)
    {
        const defaults = {}

        this.el = el || null
        this.options = merge(defaults, options)

        this.init();
    }

    public init(): void
    {
        this.slider = tns(
            {
                "container": '.CategoryMenuCarousel',
                "controlsContainer": '.CategoryMenuCarouselControls',
                "items": 5,
                "slideBy": 1,
                "loop": false,
                "rewind": false,
                "mouseDrag": true,
                "touch": true,
                "swipeAngle": false,
                "speed": 400,
                "nav": false,
                "arrowKeys": true,
                "lazyload": true,
            }
        )

        // hide prev or left control on currentIndex 0
    }
}