export default class ElementObserver {
    private observer: MutationObserver;

    constructor(
        private el: Element,
        private config: object,
        private mutationCallback: MutationCallback
    ) {
        this.init();
    }

    public init()
    {
        if (this.el === null) {
            throw new Error("No valid Element was given.");
        }

        this.observer = new MutationObserver(
            (mutations: MutationRecord[]) =>
                this.mutationCallback(mutations, this.observer)
        )
    }

    public observe() {
        this.observer.observe(this.el, this.config);
    }

    public disconnect() {
        this.observer.disconnect();
    }
}