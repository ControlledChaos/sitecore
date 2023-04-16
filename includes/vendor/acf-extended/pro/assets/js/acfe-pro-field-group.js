(function($) {

    if (typeof acf === 'undefined')
        return;

    /*
     * Duplicate Field
     */
    new acf.Model({

        actions: {
            'duplicate_field_object': 'onDuplicateObject',
        },

        onDuplicateObject: function(field, newField) {

            var label = prompt('Field label', field.prop('label'));

            if (!label || !label.length)
                return;

            if (label === field.prop('label'))
                return;

            var name = acf.applyFilters('generate_field_object_name', acf.strSanitize(label), newField);

            newField.prop('label', label);
            newField.prop('name', name);

        }

    });

    /*
     * Global Conditional Field Settings
     */
    var ConditionalLogicField = acf.models.ConditionalLogicFieldSetting;

    acf.models.ConditionalLogicFieldSetting = ConditionalLogicField.extend({

        /*
         * Source: /assets/js/acf-field-group.js:1352
         */
        renderField: function() {

            // vars
            var choices = [];
            var validFieldTypes = [];
            var cid = this.fieldObject.cid;
            var $select = this.$input('field');

            // loop
            var fieldObjects = acf.getFieldObjects();

            // ACFE Global Fields Conditional
            var acfeGlobalFields = acf.isget(acfe, 'globalFieldsConditional');

            if (acfeGlobalFields) {

                acfeGlobalFields = acfeGlobalFields.map(function(item) {

                    var $el = $('<div ' +
                        'data-id="' + item.ID + '" ' +
                        'data-key="' + item.key + '" ' +
                        'data-type="' + item.type + '" ' +
                        'data-label="' + item.label + '" ' +
                        'data-parent="' + item.parent + '" ' +
                        'data-menu_order="' + item.menu_order + '" ' +
                        'data-acfe-global-field="1"' +
                        '></div>');

                    return new acf.FieldObject($el);

                });

                fieldObjects = fieldObjects.concat(acfeGlobalFields);

            }

            fieldObjects.map(function(fieldObject) {

                // vars
                var choice = {
                    id: fieldObject.getKey(),
                    text: fieldObject.getLabel()
                };

                // bail early if is self
                if (fieldObject.cid === cid) {
                    choice.text += ' ' + acf.__('(this field)');
                    choice.disabled = true;
                }

                // get selected field conditions
                var conditionTypes = acf.getConditionTypes({
                    fieldType: fieldObject.getType()
                });

                // bail early if no types
                if (!conditionTypes.length) {
                    choice.disabled = true;
                }

                // calulate indents
                var indents = fieldObject.getParents().length;
                choice.text = '- '.repeat(indents) + choice.text;

                if (fieldObject.get('acfeGlobalField')) {
                    choice.text = 'Global Field: ' + choice.text + ' (' + choice.id + ')';
                }

                // append
                choices.push(choice);

            });

            // allow for scenario where only one field exists
            if (!choices.length) {
                choices.push({
                    id: '',
                    text: acf.__('No toggle fields available'),
                });
            }

            // render
            acf.renderSelect($select, choices);

            // set
            this.ruleData('field', $select.val());

        },

        /*
         * Source: /assets/js/acf-field-group.js:1408
         */
        renderOperator: function() {

            // bail early if no field selected
            if (!this.ruleData('field')) {
                return;
            }

            // vars
            var $select = this.$input('operator');
            var val = $select.val();
            var choices = [];

            // set saved value on first render
            // - this allows the 2nd render to correctly select an option
            if ($select.val() === null) {
                acf.renderSelect($select, [{
                    id: this.ruleData('operator'),
                    text: ''
                }]);
            }

            // get selected field
            var $field = acf.findFieldObject(this.ruleData('field'));
            var field = acf.getFieldObject($field);

            // get selected field conditions
            var conditionTypes = acf.getConditionTypes({
                fieldType: field.getType()
            });


            var operatorBuffer = [];

            // html
            conditionTypes.map(function(model) {

                if (acfe.inArray(model.prototype.operator, operatorBuffer))
                    return;

                choices.push({
                    id: model.prototype.operator,
                    text: model.prototype.label
                });

                operatorBuffer.push(model.prototype.operator);

            });

            // render
            acf.renderSelect($select, choices);

            // set
            this.ruleData('operator', $select.val());
        },

    });

    /*
     * Field Group Enhanced UI
     */
    new acf.Model({

        actions: {
            'prepare_field/name=acfe_note': 'note',
            'prepare_field/name=hide_on_screen': 'hideOnScreen',
            'prepare_field/name=acfe_permissions': 'permissions',
            'prepare_field/name=acfe_meta': 'meta',
        },

        getTab: function(field, key) {

            return field.$el.closest('.inside').find('.acf-tab-group a[data-key=' + key + ']');

        },

        hideOnScreen: function(field) {

            var $tab = this.getTab(field, 'screen');
            var tabTitle = $tab.text();

            var val = field.val();
            $tab.html(tabTitle + (val.length ? ' <span class="acfe-tab-badge">' + val.length + '</span>' : ''));

            field.on('change', function() {

                var val = field.val();
                $tab.html(tabTitle + (val.length ? ' <span class="acfe-tab-badge">' + val.length + '</span>' : ''));

            });

        },

        permissions: function(field) {

            var $tab = this.getTab(field, 'permissions');
            var tabTitle = $tab.text();

            var val = field.val();
            $tab.html(tabTitle + (val.length ? ' <span class="acfe-tab-badge">' + val.length + '</span>' : ''));

            field.on('change', function() {

                var val = field.val();
                $tab.html(tabTitle + (val.length ? ' <span class="acfe-tab-badge">' + val.length + '</span>' : ''));

            });

        },

        meta: function(field) {

            var $tab = this.getTab(field, 'advanced');
            var tabTitle = $tab.text();

            var val = field.val();
            $tab.html(tabTitle + (val ? ' <span class="acfe-tab-badge">' + val + '</span>' : ''));

            field.on('change', function() {

                var val = field.val();
                $tab.html(tabTitle + (val ? ' <span class="acfe-tab-badge">' + val + '</span>' : ''));

            });

        },

        note: function(field) {

            var $tab = this.getTab(field, 'note');
            var tabTitle = $tab.text();

            var val = field.val();
            $tab.html(tabTitle + (val.length ? ' <span class="acfe-tab-badge">1</span>' : ''));

            field.on('change', function() {

                var val = field.val();
                $tab.html(tabTitle + (val.length ? ' <span class="acfe-tab-badge">1</span>' : ''));

            });

        },

    });

    /*
     * Field: WYSIWYG
     */
    new acf.Model({

        actions: {
            'new_field/name=acfe_wysiwyg_toolbar_1': 'buttonClass',
            'new_field/name=acfe_wysiwyg_toolbar_2': 'buttonClass',
            'new_field/name=acfe_wysiwyg_toolbar_3': 'buttonClass',
            'new_field/name=acfe_wysiwyg_toolbar_4': 'buttonClass',
        },

        buttonClass: function(field) {

            field.$('.acf-button').removeClass('button-primary');

        }

    });

    /*
     * Field: Google Map
     */
    var mapEvent = false;

    new acf.Model({

        actions: {
            'google_map_init': 'mapInit',
            'new_field/name=height': 'mapHeight',
            'new_field/name=zoom': 'mapZoom',
            'new_field/name=min_zoom': 'mapMinZoom',
            'new_field/name=max_zoom': 'mapMaxZoom',
            'new_field/name=acfe_google_map_marker_icon': 'mapMarkerIcon',
            'new_field/name=acfe_google_map_marker_width': 'mapMarkerSetSize',
            'new_field/name=acfe_google_map_marker_height': 'mapMarkerSetSize',
            'new_field/name=acfe_google_map_type': 'mapType',
            'new_field/name=acfe_google_map_style': 'mapStyle',
            'new_field/name=acfe_google_map_disable_ui': 'mapDisableUI',
            'new_field/name=acfe_google_map_disable_zoom_control': 'mapDisableZoom',
            'new_field/name=acfe_google_map_disable_map_type': 'mapDisableMapType',
            'new_field/name=acfe_google_map_disable_fullscreen': 'mapDisableFullscreen',
            'new_field/name=acfe_google_map_disable_streetview': 'mapDisableStreetview',
        },

        getGoogleMap: function(field) {

            return acf.getInstance(field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-acfe_google_map_preview'));

        },

        mapInit: function(map, marker, field) {

            if (field.get('name') !== 'acfe_google_map_preview')
                return;

            google.maps.event.addListener(map, 'zoom_changed', function() {

                var zoom = acf.getInstance(field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-acfe_google_map_zooms > .acf-input > .acf-fields > .acf-field-zoom'));

                mapEvent = true;

                zoom.val(map.getZoom());

                mapEvent = false;

            });

            google.maps.event.addListener(map, 'center_changed', function() {

                var $center_lat = field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-center_lat > .acf-input > ul > li:eq(0) input');
                var $center_lng = field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-center_lat > .acf-input > ul > li:eq(1) input');

                $center_lat.val(map.getCenter().lat()).change();
                $center_lng.val(map.getCenter().lng()).change();

            });

            google.maps.event.addListener(map, 'maptypeid_changed', function() {

                var map_type = acf.getInstance(field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-acfe_google_map_type'));

                map_type.val(map.getMapTypeId());

            });

        },

        mapHeight: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('input', function(e) {

                var val = parseInt(field.val());

                if (isNaN(val))
                    val = 400;

                preview.$canvas().height(val);

            });

        },

        mapZoom: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                if (mapEvent)
                    return;

                var val = parseInt(field.val());

                preview.map.setZoom(val);

            });

        },

        mapMinZoom: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = parseInt(field.val());

                preview.map.setOptions({
                    minZoom: val
                });

            });

        },

        mapMaxZoom: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = parseInt(field.val());

                preview.map.setOptions({
                    maxZoom: val
                });

            });

        },

        mapMarkerIcon: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = field.val();

                if (val) {

                    var url = field.$('img').attr('src');

                    var $height = field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-acfe_google_map_marker_height > .acf-input > ul > li:eq(0) input');
                    var $width = field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-acfe_google_map_marker_height > .acf-input > ul > li:eq(1) input');

                    var height = parseInt($height.val());
                    var width = parseInt($width.val());

                    preview.map.marker.setIcon({
                        url: url,
                        size: new google.maps.Size(width, height),
                        scaledSize: new google.maps.Size(width, height),
                    });

                } else {

                    preview.map.marker.setIcon();

                }

            });

        },

        mapMarkerSetSize: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var icon = preview.map.marker.getIcon();

                var $height = field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-acfe_google_map_marker_height > .acf-input > ul > li:eq(0) input');
                var $width = field.$el.closest('tbody.acf-field-settings').find('> .acf-field-setting-acfe_google_map_marker_height > .acf-input > ul > li:eq(1) input');

                var height = parseInt($height.val());
                var width = parseInt($width.val());

                preview.map.marker.setIcon({
                    url: icon.url,
                    size: new google.maps.Size(width, height),
                    scaledSize: new google.maps.Size(width, height),
                });

            });

        },

        mapType: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = field.val();

                preview.map.setOptions({
                    mapTypeId: val
                });

            });

        },

        mapStyle: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = field.val();
                var json;

                try {

                    json = $.parseJSON(val);

                } catch (err) {

                    json = false

                }

                if (!val || val.trim().length === 0 || !json) {

                    preview.map.setOptions({
                        styles: ''
                    });

                    return;

                }

                preview.map.setOptions({
                    styles: json
                });

            });

        },

        mapDisableUI: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = parseInt(field.val());

                preview.map.setOptions({
                    disableDefaultUI: val
                });

                var disable_zoom = acf.getFields({
                    sibling: field.$el,
                    name: 'acfe_google_map_disable_zoom_control',
                    suppressFilters: true,
                });

                disable_zoom = disable_zoom[0];

                var disable_map_type = acf.getFields({
                    sibling: field.$el,
                    name: 'acfe_google_map_disable_map_type',
                    suppressFilters: true,
                });

                disable_map_type = disable_map_type[0];

                var disable_fullscreen = acf.getFields({
                    sibling: field.$el,
                    name: 'acfe_google_map_disable_fullscreen',
                    suppressFilters: true,
                });

                disable_fullscreen = disable_fullscreen[0];

                var disable_streeview = acf.getFields({
                    sibling: field.$el,
                    name: 'acfe_google_map_disable_streetview',
                    suppressFilters: true,
                });

                disable_streeview = disable_streeview[0];

                if (val) {

                    disable_zoom.switchOn();
                    disable_zoom.$input().change();

                    disable_map_type.switchOn();
                    disable_map_type.$input().change();

                    disable_fullscreen.switchOn();
                    disable_fullscreen.$input().change();

                    disable_streeview.switchOn();
                    disable_streeview.$input().change();

                } else {

                    disable_zoom.switchOff();
                    disable_zoom.$input().change();

                    disable_map_type.switchOff();
                    disable_map_type.$input().change();

                    disable_fullscreen.switchOff();
                    disable_fullscreen.$input().change();

                    disable_streeview.switchOff();
                    disable_streeview.$input().change();

                }

            });

        },

        mapDisableZoom: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = parseInt(field.val());

                preview.map.setOptions({
                    zoomControl: !val
                });
                preview.map.setOptions({
                    scrollwheel: !val
                });

            });

        },

        mapDisableMapType: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = parseInt(field.val());

                preview.map.setOptions({
                    mapTypeControl: !val
                });

            });

        },

        mapDisableFullscreen: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = parseInt(field.val());

                preview.map.setOptions({
                    fullscreenControl: !val
                });

            });

        },

        mapDisableStreetview: function(field) {

            var preview = this.getGoogleMap(field);

            if (!preview)
                return;

            field.on('change', function(e) {

                var val = parseInt(field.val());

                preview.map.setOptions({
                    streetViewControl: !val
                });

            });

        }

    });

    /*
     * Flexible Content: Layouts Locations
     */
    new acf.Model({

        wait: 'ready',

        events: {
            'click .add-location-rule': 'onClickAddRule',
            'click .add-location-group': 'onClickAddGroup',
            'click .remove-location-rule': 'onClickRemoveRule',
            'change .refresh-location-rule': 'onChangeRemoveRule'
        },

        initialize: function() {
            this.$el = $('.acf-field-object-flexible-content');
        },

        onClickAddRule: function(e, $el) {
            this.addRule($el.closest('tr'));
        },

        onClickRemoveRule: function(e, $el) {
            this.removeRule($el.closest('tr'));
        },

        onChangeRemoveRule: function(e, $el) {
            this.changeRule($el.closest('tr'));
        },

        onClickAddGroup: function(e, $el) {
            this.addGroup($el.closest('.rule-groups'));
        },

        addRule: function($tr) {
            acf.duplicate($tr);
        },

        removeRule: function($tr) {

            $tr.closest('.acf-field-object').change();

            if ($tr.siblings('tr').length === 0) {
                $tr.closest('.rule-group').remove();
            } else {
                $tr.remove();
            }

        },

        changeRule: function($rule) {

            // vars
            var $group = $rule.closest('.rule-group');
            var prefix = $rule.find('td.param select').attr('name').replace('[param]', '');

            // ajaxdata
            var ajaxdata = {};
            ajaxdata.action = 'acfe/layout/render_location_rule';
            ajaxdata.prefix = prefix;
            ajaxdata.rule = acf.serialize($rule, prefix);
            ajaxdata.rule.id = $rule.data('id');
            ajaxdata.rule.group = $group.data('id');

            // temp disable
            acf.disable($rule.find('td.value'));

            // ajax
            $.ajax({
                url: acf.get('ajaxurl'),
                data: acf.prepareForAjax(ajaxdata),
                type: 'post',
                dataType: 'html',
                success: function(html) {
                    if (!html) return;
                    $rule.replaceWith(html);
                }
            });
        },

        addGroup: function($ruleGroups) {

            // vars
            var $group = $ruleGroups.find('.rule-group:last');

            // duplicate
            $group2 = acf.duplicate($group);

            // update h4
            $group2.find('h4').text(acf.__('or'));

            // remove all tr's except the first one
            $group2.find('tr').not(':first').remove();

        }
    });

})(jQuery);