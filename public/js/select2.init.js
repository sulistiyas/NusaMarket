// =============================================
// select2.init.js — Global Select2 Initializer
// =============================================

export function initSelect2() {
    if (typeof window.$ !== 'undefined' && window.$.fn && window.$.fn.select2) {
        window.$('.select2').each(function () {
            const $this = window.$(this);

            if ($this.hasClass('select2-hidden-accessible')) {
                return;
            }

            const placeholder = $this.attr('data-placeholder') || $this.data('placeholder') || 'Pilih Option';
            const rawAjaxUrl = $this.attr('data-url') || $this.data('url');
            const ajaxUrl = (typeof rawAjaxUrl === 'string' && rawAjaxUrl.trim() !== '' && rawAjaxUrl !== 'undefined') ? rawAjaxUrl.trim() : null;

            const formatOption = function (option) {
                if (!option.id) {
                    return option.text || placeholder;
                }
                const $element = window.$(option.element);
                const icon = $element.length ? ($element.data('icon') || option.icon) : option.icon;
                if (icon) {
                    const iconClass = icon.startsWith('fa') ? icon : `fa ${icon}`;
                    return `<span><i class="fas ${iconClass} select2-option-icon"></i> ${option.text}</span>`;
                }
                return option.text;
            };

            const formatSelection = function (option) {
                if (!option.id) {
                    return option.text || placeholder;
                }
                const $element = window.$(option.element);
                const icon = $element.length ? ($element.data('icon') || option.icon) : option.icon;
                if (icon) {
                    const iconClass = icon.startsWith('fa') ? icon : `fa ${icon}`;
                    return `<span><i class="fas ${iconClass}" style="color: var(--primary); margin-right: 6px;"></i> ${option.text}</span>`;
                }
                return option.text;
            };

            const options = {
                placeholder: placeholder,
                allowClear: true,
                width: '100%',
                templateResult: formatOption,
                templateSelection: formatSelection,
                escapeMarkup: function (m) { return m; }
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
                        const items = data.data || data;
                        return {
                            results: items.map(item => ({
                                id: item.id,
                                text: item.name || item.title || item.text,
                                icon: item.icon
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

if (typeof window !== 'undefined') {
    window.initSelect2 = initSelect2;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSelect2);
    } else {
        initSelect2();
    }

    window.addEventListener('load', initSelect2);
}
