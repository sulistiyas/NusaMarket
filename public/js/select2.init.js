// =============================================
// select2.init.js — Global Select2 Initializer
// =============================================

document.addEventListener('DOMContentLoaded', () => {
    initSelect2();
});

export function initSelect2() {
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').each(function () {
            const $this = $(this);
            const placeholder = $this.data('placeholder') || 'Pilih Option';
            const ajaxUrl = $this.data('url');

            const options = {
                placeholder: placeholder,
                allowClear: true,
                width: '100%',
            };

            if (ajaxUrl) {
                options.ajax = {
                    url: ajaxUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.data.map(item => ({
                                id: item.id,
                                text: item.name || item.title || item.text
                            }))
                        };
                    },
                    cache: true
                };
            }

            $this.select2(options);
        });
    }
}
