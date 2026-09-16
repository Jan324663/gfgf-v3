(function (blocks, blockEditor, components, element, i18n) {
    'use strict';

    var el = element.createElement;
    var Fragment = element.Fragment;
    var InspectorControls = blockEditor.InspectorControls;
    var useBlockProps = blockEditor.useBlockProps;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var __ = i18n.__;

    function control(props, key, label, multiline) {
        var Component = multiline ? TextareaControl : TextControl;
        return el(Component, {
            key: key,
            label: label,
            value: props.attributes[key] || '',
            onChange: function (value) {
                var update = {};
                update[key] = value;
                props.setAttributes(update);
            }
        });
    }

    blocks.registerBlockType('gfgf-service-docs/search', {
        edit: function (props) {
            var attributes = props.attributes;
            var blockProps = useBlockProps({ className: 'service-docs-editor-preview' });

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: __('Suche', 'gfgf-service-docs'), initialOpen: true },
                        control(props, 'searchHeading', __('Überschrift', 'gfgf-service-docs')),
                        control(props, 'searchLabel', __('Feldbeschriftung', 'gfgf-service-docs')),
                        control(props, 'searchPlaceholder', __('Platzhalter', 'gfgf-service-docs')),
                        control(props, 'searchButtonLabel', __('Suchbutton', 'gfgf-service-docs')),
                        control(props, 'emptySearchNotice', __('Hinweis vor der Suche', 'gfgf-service-docs'), true),
                        control(props, 'noResultsNotice', __('Hinweis ohne Treffer', 'gfgf-service-docs'), true)
                    ),
                    el(PanelBody, { title: __('Treffer', 'gfgf-service-docs'), initialOpen: false },
                        control(props, 'detailsLabel', __('Details-Schalter', 'gfgf-service-docs')),
                        control(props, 'requestButtonLabel', __('Anfragebutton', 'gfgf-service-docs'))
                    ),
                    el(PanelBody, { title: __('Anfrageformular', 'gfgf-service-docs'), initialOpen: false },
                        control(props, 'requestHeading', __('Überschrift', 'gfgf-service-docs')),
                        control(props, 'requestIntro', __('Einleitung', 'gfgf-service-docs'), true),
                        control(props, 'firstNameLabel', __('Vorname', 'gfgf-service-docs')),
                        control(props, 'lastNameLabel', __('Nachname', 'gfgf-service-docs')),
                        control(props, 'emailLabel', __('E-Mail', 'gfgf-service-docs')),
                        control(props, 'memberLabel', __('Mitglieds-Checkbox', 'gfgf-service-docs')),
                        control(props, 'messageLabel', __('Nachricht', 'gfgf-service-docs')),
                        control(props, 'submitLabel', __('Absende-Button', 'gfgf-service-docs')),
                        control(props, 'privacyNotice', __('Datenschutzhinweis', 'gfgf-service-docs'), true),
                        control(props, 'successNotice', __('Erfolgsbestätigung', 'gfgf-service-docs'), true)
                    )
                ),
                el('div', blockProps,
                    el('span', { className: 'service-docs-editor-preview__label' }, __('Dynamischer Block', 'gfgf-service-docs')),
                    el('h2', {}, attributes.searchHeading),
                    el('label', {}, attributes.searchLabel),
                    el('div', { className: 'service-docs-editor-preview__form' },
                        el('div', { className: 'service-docs-editor-preview__input' }, attributes.searchPlaceholder),
                        el('div', { className: 'service-docs-editor-preview__button' }, attributes.searchButtonLabel)
                    ),
                    el('p', {}, attributes.emptySearchNotice),
                    el('small', {}, __('Treffer und Anfrageformular werden dynamisch im Frontend ausgegeben. Alle Beschriftungen können rechts in den Block-Einstellungen geändert werden.', 'gfgf-service-docs'))
                )
            );
        },
        save: function () {
            return null;
        }
    });
}(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n));
