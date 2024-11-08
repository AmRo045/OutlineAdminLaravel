const copyToClipboardExec = (value) => {
    const textArea = document.createElement("textarea");
    textArea.value = value;

    document.body.appendChild(textArea);

    textArea.focus();
    textArea.select();

    document.execCommand('copy');
    document.body.removeChild(textArea);
};

window.copyToClipboard = async (value, message = 'Copied') => {
    if (navigator && navigator.clipboard && navigator.clipboard.writeText) {
        try {
            await navigator.clipboard.writeText(value);
            alert(message);
        } catch (err) {
            copyToClipboardExec(value);
            alert(message);
        }
    } else {
        copyToClipboardExec(value);
        alert(message);
    }
};
