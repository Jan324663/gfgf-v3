(() => {
    const dialog = document.querySelector('#archive-collection-dialog');
    const triggers = document.querySelectorAll(
        '.archive-collection__more .wp-block-button__link, button.archive-collection__more'
    );

    if (!dialog || !triggers.length || typeof dialog.showModal !== 'function') {
        return;
    }

    const dialogTitle = dialog.querySelector('#archive-dialog-title');
    const dialogText = dialog.querySelector('#archive-dialog-text');
    const dialogDownload = dialog.querySelector('.archive-collection-dialog__download');
    const dialogDownloadLabel = dialog.querySelector('.archive-collection-dialog__download-label');
    const closeButton = dialog.querySelector('.archive-collection-dialog__close');
    let previousTrigger = null;

    const closeDialog = () => {
        if (dialog.open) {
            dialog.close();
        }
    };

    triggers.forEach((trigger) => {
        trigger.setAttribute('aria-haspopup', 'dialog');
        trigger.setAttribute('aria-controls', 'archive-collection-dialog');

        trigger.addEventListener('click', (event) => {
            const collection = trigger.closest('.archive-collection');
            const source = collection?.querySelector('.archive-collection__dialog-source');
            const title = collection?.querySelector('h3')?.textContent.trim() || trigger.dataset.dialogTitle || '';
            const text = source?.querySelector('.archive-collection__dialog-text')?.textContent.trim()
                || trigger.dataset.dialogText
                || '';
            const download = source?.querySelector('.archive-collection__dialog-download');
            const downloadUrl = download?.href || trigger.dataset.dialogDownloadUrl || '';
            const downloadLabel = download?.textContent.trim() || trigger.dataset.dialogDownloadLabel || '';

            event.preventDefault();
            previousTrigger = trigger;
            dialogTitle.textContent = title;
            dialogText.textContent = text;

            if (downloadUrl && downloadLabel) {
                dialogDownload.href = downloadUrl;
                dialogDownloadLabel.textContent = downloadLabel;
                dialogDownload.hidden = false;
            } else {
                dialogDownload.hidden = true;
                dialogDownload.removeAttribute('href');
                dialogDownloadLabel.textContent = '';
            }

            dialog.showModal();
            closeButton.focus();
        });
    });

    closeButton.addEventListener('click', closeDialog);

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            closeDialog();
        }
    });

    dialog.addEventListener('close', () => {
        if (previousTrigger && document.contains(previousTrigger)) {
            previousTrigger.focus();
        }
    });
})();
