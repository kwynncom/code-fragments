// extension.js
import St from 'gi://St';
import Gio from 'gi://Gio';
import { Extension } from 'resource:///org/gnome/shell/extensions/extension.js';
import * as Main from 'resource:///org/gnome/shell/ui/main.js';

export default class TestBatt extends Extension {
    enable() {
        this.label = new St.Label({ text: 'v13', style_class: 'panel-button' });
        Main.panel._rightBox.insert_child_at_index(this.label, 1);

        // THIS IS THE ONLY LINE THAT WORKS IN NESTED TODAY
        Gio.DBus.session.signal_subscribe(
            null, null, null, '/test/batt', null, 0,
            (c, s, p, i, sig, params) => {
                if (params?.n_children()) {
                    const v = params.get_child_value(0);
                    const txt = v.deepUnpack?.() ?? v.get_string?.()[0] ?? v;
                    this.label.text = String(txt).slice(0,8);
                }
            }
        );
    }
    disable() { this.label?.destroy(); }
}