(() => {
    const dialog = document.querySelector('#archive-collection-dialog');
    const triggers = document.querySelectorAll('.archive-collection__more');

    if (!dialog || !triggers.length || typeof dialog.showModal !== 'function') {
        return;
    }

    const title = dialog.querySelector('#archive-dialog-title');
    const text = dialog.querySelector('#archive-dialog-text');
    const download = dialog.querySelector('.archive-collection-dialog__download');
    const downloadLabel = dialog.querySelector('.archive-collection-dialog__download-label');
    const closeButton = dialog.querySelector('.archive-collection-dialog__close');
    let previousTrigger = null;

    const closeDialog = () => {
        if (dialog.open) {
            dialog.close();
        }
    };

    triggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            previousTrigger = trigger;
            title.textContent = trigger.dataset.dialogTitle || '';
            text.textContent = trigger.dataset.dialogText || '';

            if (trigger.dataset.dialogDownloadUrl && trigger.dataset.dialogDownloadLabel) {
                download.href = trigger.dataset.dialogDownloadUrl;
                downloadLabel.textContent = trigger.dataset.dialogDownloadLabel;
                download.hidden = false;
            } else {
                download.hidden = true;
                download.removeAttribute('href');
                downloadLabel.textContent = '';
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
