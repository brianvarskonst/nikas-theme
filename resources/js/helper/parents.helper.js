export function parents(node, selector) {
    let current = node,
        list    = [];

    while(current.parentNode !== null && current.parentNode !== document.documentElement) {
        list.push(current.parentNode);
        current = current.parentNode;
    }

    let result = null;

    list.forEach(element => {
        if (element.matches(selector)) {
            result = element
        }
    })

    return result
}