const selectors = {
    wrapper: '#images-wrapper',
    item: '.images-wrapper-item',
    removeBtn: '.images-wrapper-item-remove',
}

$(document).ready(function () {
    $(selectors.removeBtn).on('click', function (e) {
        e.preventDefault();

        const btn = $(this);

        btn.addClass('disabled');

        axios.delete(
            btn.data('url'), {
                responseType: 'json',
            },
        ).then(response => {
            iziToast.success({
                title: 'Successful delete',
                message: response.data.message,
                position: 'topRight',
            });

            btn.parent().remove();
        }).catch(error => {
            iziToast.success({
                message: error.data.message,
                position: 'topRight',
            });

            btn.removeClass('disabled');
        }).finally(() => {
            btn.removeClass('disabled');
        });
    });
});


