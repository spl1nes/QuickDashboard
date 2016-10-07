(function (jsOMS)
{
    "use strict";

    jsOMS.Application = function ()
    {
        jsOMS.Autoloader.initPreloaded();
        this.eventManager    = new jsOMS.Event.EventManager();
        this.uiManager       = new jsOMS.UI.UIManager(this);

        this.setActions();
        this.uiManager.bind();
    };

    jsOMS.Application.prototype.setResponseMessages = function ()
    {
        this.responseManager.add('notify', notifyMessage);
        this.responseManager.add('validation', formValidationMessage);
        this.responseManager.add('redirect', redirectMessage);
        this.responseManager.add('reload', reloadMessage);
    };

    jsOMS.Application.prototype.setActions = function ()
    {
        this.uiManager.getActionManager().add('redirect', redirectMessage);
    };
}(window.jsOMS = window.jsOMS || {}));

jsOMS.ready(function ()
{
    "use strict";

    window.omsApp = new jsOMS.Application();
    window.onbeforeunload = function() {
        jsOMS.removeClass(document.getElementById('darken'), 'hidden');
        jsOMS.removeClass(document.getElementById('loader'), 'hidden');
    }
});
