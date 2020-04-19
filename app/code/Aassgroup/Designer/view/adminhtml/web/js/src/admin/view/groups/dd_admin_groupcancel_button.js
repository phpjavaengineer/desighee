var DD_admin_groupcancel_button= DD_button.extend({
    class_name: 'dd-admin-groupcancel-button',
    model: 'DD_Admin_ImagesSelected_Model',

    init: function (parent) {
        var options = {
            parent: parent,
            class: this.class_name,
            text: this._('cancel'),
            fa_addon: 'fa fa-minus-circle'
        }
        this._super(options);
    },

    _callBackModel: function (model) {
        model.addGroupCancelClick(this.self);
    }

});

