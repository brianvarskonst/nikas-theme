import CategoryMenu from "./module/category-menu.module"
import "latte-carousel/dist/latte-carousel.min.css"
import { Carousel } from "latte-carousel"

new CategoryMenu(
    document.querySelector('[data-category-menu]'),
    {}
)