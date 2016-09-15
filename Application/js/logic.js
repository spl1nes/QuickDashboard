(function (jsOMS)
{
    "use strict";

    jsOMS.Application = function ()
    {
        jsOMS.Autoloader.initPreloaded();
        this.eventManager    = new jsOMS.Event.EventManager();
        this.uiManager       = new jsOMS.UI.UIManager(this);
        this.inputManager    = new jsOMS.UI.Input.InputManager();

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
        this.uiManager.getActionManager().add('dom', domAction);
    };
}(window.jsOMS = window.jsOMS || {}));

jsOMS.ready(function ()
{
    "use strict";

    window.omsApp = new jsOMS.Application();
});
