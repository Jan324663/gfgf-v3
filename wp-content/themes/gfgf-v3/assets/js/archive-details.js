(() => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    class ArchiveDisclosure {
        constructor(element) {
            this.element = element;
            this.summary = element.querySelector('summary');
            this.content = element.querySelector('.archive-collection__details-content');
            this.animation = null;
            this.isClosing = false;
            this.isExpanding = false;

            if (!this.summary || !this.content || typeof element.animate !== 'function') {
                return;
            }

            this.summary.addEventListener('click', (event) => this.handleClick(event));
        }

        handleClick(event) {
            if (reduceMotion.matches) {
                return;
            }

            event.preventDefault();
            this.element.style.overflow = 'hidden';

            if (this.isClosing || !this.element.open) {
                this.open();
            } else {
                this.close();
            }
        }

        getClosedHeight() {
            const style = window.getComputedStyle(this.element);
            const borders = parseFloat(style.borderTopWidth) + parseFloat(style.borderBottomWidth);

            return this.summary.offsetHeight + borders;
        }

        animateHeight(startHeight, endHeight, open) {
            if (this.animation) {
                this.animation.cancel();
            }

            this.isClosing = !open;
            this.isExpanding = open;
            this.animation = this.element.animate(
                { height: [`${startHeight}px`, `${endHeight}px`] },
                { duration: 220, easing: 'cubic-bezier(0.4, 0, 0.2, 1)' }
            );
            this.animation.onfinish = () => this.finish(open);
        }

        open() {
            const startHeight = this.element.offsetHeight;
            this.element.style.height = `${startHeight}px`;
            this.element.open = true;

            window.requestAnimationFrame(() => {
                const endHeight = this.getClosedHeight() + this.content.offsetHeight;
                this.animateHeight(startHeight, endHeight, true);
            });
        }

        close() {
            this.animateHeight(this.element.offsetHeight, this.getClosedHeight(), false);
        }

        finish(open) {
            this.element.open = open;
            this.element.style.height = '';
            this.element.style.overflow = '';
            this.animation = null;
            this.isClosing = false;
            this.isExpanding = false;
        }
    }

    document.querySelectorAll('.archive-collection__details').forEach((element) => {
        new ArchiveDisclosure(element);
    });
})();
