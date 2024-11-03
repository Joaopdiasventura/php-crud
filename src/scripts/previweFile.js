function previewFile() {
    const preview = document.getElementById('previewImage');
    const file = document.getElementById('image').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", () => {
        preview.src = reader.result;
    }, false);

    if (file)
        reader.readAsDataURL(file);

}