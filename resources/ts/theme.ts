import CategoryMenu from "./module/category-menu.module"
import "latte-carousel/dist/latte-carousel.min.css"
import { Carousel } from "latte-carousel"

new CategoryMenu(
    document.querySelector('[data-category-menu]'),
    {}
)

new Carousel(
    ".CategoryMenuCarousel",
    {
        count: 5,
        move: 1,
        touch: true,
        mode: "align",
        buttons: true,
        dots: false,
        rewind: false,
        autoplay: 0,
        animation: 500,
        responsive: {
            "0": { count: 1.5, buttons: false },
            "480": { count: 2.5, buttons: false },
            "768": { count: 3, touch: false },
            "1440": { count: 4, touch: false },
        },
    }
)