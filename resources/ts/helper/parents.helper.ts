export function parents(node: Node & ParentNode | null, selector: string): Element | null {
    let current = node,
        list    = [];

    while(current.parentNode !== null && current.parentNode !== document.documentElement) {
        list.push(current.parentNode);
        current = current.parentNode;
    }

    let result = null;

    list.forEach((element: Element) => {
        if (element.matches(selector)) {
            result = element
        }
    })

    return result;
}