/**
 * @Package Module: Aassgroup_Designer
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @copyright : April 2020
 */
var DD_Layer_Main = DD_Layer_Base.extend({
    init: function(options) {
        fabric.Object.prototype.transparentCorners = false;
        //this._l().canvas.selection = false;
        options.nocontrols = true;
        options.noborders = true;
        options.noselectable = true;
        options.left = 0;
        options.top = 0;
        options.noChangeSize = true;
        options.parent = this._l().getBgCanvas();
        options.mainBg = true;
        new DD_Layer_Img(options);
        return;
    }
});


