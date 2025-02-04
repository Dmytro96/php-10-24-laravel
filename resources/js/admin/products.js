const selectors = {
    thumbnail: {
        input: '#thumbnail',
        preview: '#thumbnail-preview',
    },
    gallery: {
        wrapper: '#images-wrapper',
        input: '#images',
    }
}

const galleryPreviewTemplate = "<div class='mb-4 col-md-3'><img src='#' class='img-thumbnail' width='100%'></div>";

$(document).ready(function () {
    $(selectors.thumbnail.input).on('change', function () {
        const reader = new FileReader();
        const file = this.files[0];

        reader.onloadend = function (e) {
            $(selectors.thumbnail.preview).attr('src', e.target.result).show();
        }
        reader.readAsDataURL(file);
    });

    $(selectors.gallery.input).on('change', function () {
        $(selectors.gallery.wrapper).html('');

        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onloadend = function (e) {
                const img = galleryPreviewTemplate.replace('#', e.target.result);
                $(selectors.gallery.wrapper).append(img);
            }
            reader.readAsDataURL(file);
        })

    });
});
