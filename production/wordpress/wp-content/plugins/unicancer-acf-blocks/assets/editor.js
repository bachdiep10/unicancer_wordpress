(function (blocks, element, components, blockEditor, serverSideRender, i18n) {
    const el = element.createElement;
    const InspectorControls = blockEditor.InspectorControls;
    const PanelBody = components.PanelBody;
    const TextControl = components.TextControl;
    const TextareaControl = components.TextareaControl;
    const RangeControl = components.RangeControl;
    const SSR = serverSideRender;

    const definitions = {
        'patient-stories': { title: 'Câu chuyện bệnh nhân', icon: 'format-quote', hasItems: true },
        'consultation-form': { title: 'Form tư vấn miễn phí', icon: 'feedback', hasItems: false },
        'treatments': { title: 'Kỹ thuật điều trị', icon: 'shield-alt', hasItems: true },
        'mdt-doctors': { title: 'Đội ngũ MDT', icon: 'groups', hasItems: true }
    };

    Object.keys(definitions).forEach(function (name) {
        const definition = definitions[name];
        blocks.registerBlockType('unicancer/' + name, {
            apiVersion: 2,
            title: definition.title,
            description: 'Block động UNI-ASIA, tương thích ACF miễn phí.',
            icon: definition.icon,
            category: 'unicancer',
            supports: { align: ['wide', 'full'], html: false },
            edit: function (props) {
                const attrs = props.attributes;
                const controls = [
                    el(TextControl, { label: 'Tiêu đề', value: attrs.title || '', onChange: v => props.setAttributes({ title: v }) }),
                    name !== 'consultation-form' && el(TextareaControl, { label: 'Mô tả', value: attrs.description || '', onChange: v => props.setAttributes({ description: v }) }),
                    name !== 'consultation-form' && el(TextControl, { label: 'Chữ liên kết', value: attrs.linkText || '', onChange: v => props.setAttributes({ linkText: v }) }),
                    name !== 'consultation-form' && el(TextControl, { label: 'URL liên kết', value: attrs.linkUrl || '', onChange: v => props.setAttributes({ linkUrl: v }) }),
                    definition.hasItems && el(RangeControl, { label: 'Số mục hiển thị', value: attrs.itemsCount || 4, min: 1, max: 8, onChange: v => props.setAttributes({ itemsCount: v }) }),
                    name === 'consultation-form' && el(TextControl, { label: 'Nhãn nút gửi', value: attrs.buttonText || '', onChange: v => props.setAttributes({ buttonText: v }) })
                ].filter(Boolean);
                const inlineEditor = props.isSelected ? el('div', { className: 'ucb-editor-fields' },
                    el('div', { className: 'ucb-editor-fields__title' }, 'Sửa nội dung block'),
                    controls.map(function (control, index) { return el('div', { key: index, className: 'ucb-editor-fields__field' }, control); }),
                    el('p', { className: 'ucb-editor-fields__help' }, definition.hasItems ? 'Nội dung từng thẻ được sửa trong menu quản trị tương ứng: Bác sĩ, Điều trị hoặc Câu chuyện bệnh nhân.' : 'Các yêu cầu gửi từ form được lưu trong menu Yêu cầu tư vấn.')
                ) : null;
                return el(element.Fragment, {},
                    el(InspectorControls, {}, el(PanelBody, { title: 'Thiết lập UNI-ASIA', initialOpen: true }, controls)),
                    el('div', blockEditor.useBlockProps(), inlineEditor, el(SSR, { block: 'unicancer/' + name, attributes: attrs }))
                );
            },
            save: function () { return null; }
        });
    });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.serverSideRender, window.wp.i18n);
