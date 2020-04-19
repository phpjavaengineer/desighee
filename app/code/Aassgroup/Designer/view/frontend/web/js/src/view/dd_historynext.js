/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */
var DD_historyNextButton = DD_button.extend({
    object_id: 'dd-history-next-button',
    class_name: 'dd-history-controls',
    
    init: function(parent) {
        var options = {
            parent: parent,
            id: this.object_id,
            class: this.class_name,
            text: this._('next')
        }
        this._super(options);
    }
});


