var DD_admin_clear_button = DD_button.extend({
    class_name: 'dd-admin-clear-button',
    model: 'DD_Admin_ImagesSelected_Model',

    init: function (parent) {
        var options = {
            parent: parent,
            class: this.class_name,
            text: this._('clear_all'),
            fa_addon: ' fa fa-times '
        }
        this._super(options);
    },

    _callBackModel: function (model) {
        model.clearClickEvents(this.self);
    }


});
