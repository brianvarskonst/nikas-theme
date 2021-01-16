export function children(node: Node & ChildNode | null, selector: string): Element | null {
    let current = node,
        list    = [];

    while(current.firstChild !== null && current.firstChild !== document.documentElement && current.hasChildNodes()) {
        list.push(current.firstChild);
        current = current.firstChild;
    }

    let result = null;

    list.forEach((element: Element) => {
        if (element.matches(selector)) {
            result = element
        }
    })

    return result;
}