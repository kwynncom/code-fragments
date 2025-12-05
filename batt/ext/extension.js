// import GLib from 'gi://GLib';
// import Gio from 'gi://Gio';
import Clutter from 'gi://Clutter';
import St from 'gi://St';
import { Extension } from 'resource:///org/gnome/shell/extensions/extension.js';
import * as Main from 'resource:///org/gnome/shell/ui/main.js';

export default class BattExtension extends Extension {
    enable() {
        // Create the label
        this._label = new St.Label({
            text: 'B2',
            style_class: 'panel-button',  // Optional: match panel style
            y_align: Clutter.ActorAlign.CENTER,  // Correct enum
            // Or use: y_align: 2  (if you prefer numeric)
        });

        // Insert into the status area (right box), position 1 (after date/time)
        Main.panel._rightBox.insert_child_at_index(this._label, 1);
    }

    disable() {
        if (this._label) {
            Main.panel._rightBox.remove_child(this._label);
            this._label.destroy();
            this._label = null;
        }
    }
}

function init() {
    return new BattExtension();
}